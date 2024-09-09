# Innova-play

Innova-play é um aplicativo simples de indexação e gerenciamento de vídeos desenvolvido em PHP com suporte a SQLite. O app permite que os usuários façam login, adicionem, editem, excluam vídeos e gerenciem capas de vídeos, tudo com controle de sessão e uma interface amigável.

## Funcionalidades

- **Login de Usuário**: O app possui um usuário padrão para testes.
  - Email: `teste@teste.com.br`
  - Senha: `123456`
  
- **Gerenciamento de Vídeos**:
  - Adicionar novos vídeos com título, url e capa.
  - Editar informações de vídeos existentes.
  - Excluir vídeos.
  
- **Banco de Dados**: O app utiliza SQLite para armazenar as informações dos vídeos e gerenciar sessões de usuários.

- **Controle de Sessão**: Apenas usuários autenticados podem acessar e gerenciar vídeos.

## Tecnologias Utilizadas

- **PHP**: Linguagem de programação do lado do servidor.
- **SQLite**: Banco de dados leve e embutido.
- **PSR-4 Autoloading**: Carregamento automático das classes com padrão PSR-4.
- **Composer**: Gerenciador de dependências do PHP.

## Dependências

Este projeto utiliza as seguintes bibliotecas através do Composer:

``json
{
    "autoload": {
        "psr-4": {
            "Innova\\Mvc\\": "src/"
        }
    },
    "require": {
        "psr/http-message": "^2.0",
        "nyholm/psr7": "^1.8",
        "nyholm/psr7-server": "^1.1",
        "psr/http-server-handler": "^1.0",
        "psr/container": "^2.0",
        "php-di/php-di": "^7.0",
        "league/plates": "^3.5"
    }
}``

## Tecnologias Utilizadas

- psr/http-message: Interface de mensagens HTTP.
- nyholm/psr7: Implementação PSR-7 para lidar com requisições e respostas HTTP.
- php-di/php-di: Contêiner de Injeção de Dependências.
- league/plates: Sistema de templates para renderizar as páginas HTML.

## Instalação

- PHP (>=7.4)
- Composer (para gerenciar dependências)
- XAMPP

## Passos para Instalar

## Passos para Instalar

```bash
# 1. Clone o repositório do projeto:
git clone https://github.com/seu-usuario/innova-play.git
cd innova-play

# 2. Instale as dependências utilizando o Composer:
composer install

# 3. Inicie o servidor local:
php -S localhost:8080 -t public/

# 4. Acesse o app pelo navegador. O usuário de login padrão é:
# Email: teste@teste.com.br
# Senha: 123456




