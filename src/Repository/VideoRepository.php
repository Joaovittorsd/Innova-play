<?php

declare(strict_types=1);

namespace Innova\Mvc\Repository;

use Innova\Mvc\Entity\Video;
use Exception;
use PDO;

class VideoRepository
{
    public function __construct(private PDO $pdo)
    {
    }

     /**
     *  @return Video[]
     */
    public function all() 
    {
        $videoList = $this->pdo
            ->query('SELECT * FROM videos;')
            ->fetchAll(PDO::FETCH_ASSOC);

            return array_map(
                $this->hydrateVideo(...),
                $videoList
            );
    }

    public function add(Video $video): bool
    {
        $sql = 'INSERT INTO videos (url, title, image_path) VALUES (:url, :title, :image_path);';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue('url', $video->url);
        $stmt->bindValue('title', $video->title);
        $stmt->bindValue('image_path', $video->getFilepath());

        $result = $stmt->execute();
        $id = $this->pdo->lastInsertId();

        $video->setId(intval($id));

        return $result;
    }

    public function remove(int $id): bool
    {
        $this->removeCover($id);

        $sql = 'DELETE FROM videos WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue('id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    public function update(Video $video): bool
    {
        $updateImageSql= '';
        if ($video->getFilepath() !== null) {
            $updateImageSql= ', image_path = :image_path';
        }
        $sql = "UPDATE videos SET 
                    url = :url, 
                    title = :title 
                    $updateImageSql
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue('url', $video->url);
        $stmt->bindValue('title', $video->title);
        $stmt->bindValue(':id', $video->id, PDO::PARAM_INT);

        if ($video->getFilepath() !== null) {
            $stmt->bindValue('image_path', $video->getFilepath());
        }
        
        return $stmt->execute();
    }

    public function removeCover(int $id): bool
    {
        $sql = 'SELECT image_path FROM videos WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue('id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result && isset($result['image_path'])) {
            $imagePath = $result['image_path'];
            
            if ($this->removeImageFromFileSystem($imagePath)) {
                return $this->updateImagePathInDatabase($id, null);
            }
        }
        return false;
    }

    private function removeImageFromFileSystem(string $imagePath): bool
    {
        $fullImagePath = __DIR__ . '/../../public/img/uploads/' . $imagePath;

        if (file_exists($fullImagePath)) {
            return unlink($fullImagePath);
        }
        return false;
    }

    private function updateImagePathInDatabase(int $id, ?string $imagePath): bool
    {
        $sql = 'UPDATE videos SET image_path = :image_path WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue('image_path', $imagePath, PDO::PARAM_STR);
        $stmt->bindValue('id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function find(int $id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM videos WHERE id = :id');
        $stmt->bindValue('id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $this->hydrateVideo($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    private function hydrateVideo(array $videoData): Video
    {
        if (isset($videoData[0]) && is_array($videoData[0])) {
            $videoData = $videoData[0];
        }
        
        if (!isset($videoData['url'], $videoData['title'], $videoData['id'])) {
            throw new Exception('Dados inválidos no array.');
        }

        $video = new Video($videoData['url'], $videoData['title']);
        $video->setId($videoData['id']);

        if ($videoData['image_path'] !== null) {
            $video->setFilePath($videoData['image_path']);
        }
        
        return $video;
    }
}