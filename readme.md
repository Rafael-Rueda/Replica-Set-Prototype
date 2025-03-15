 # Usando Docker para criar um protótipo de aplicação Replica-Set com PHP.
## Iniciar com Docker e MongoDB

### Instalação de aplicativos necessários
- **Passo 1:** Baixar e instalar o Docker, disponível em: https://www.docker.com/ (Instalar com WSL que é mais leve que o Hyper-V)
---
- **Passo 2:** Baixar e instalar o MongoSH, disponível em: https://www.mongodb.com/try/download/shell
---
- **Passo 3:** Baixar e instalar o VSCODE, disponível em: https://code.visualstudio.com/

### Configurando as imagens do Docker

```shell
docker pull php:8.2-apache
```

```shell
docker pull mongo:latest
```
### Criando os containers Docker
- **Passo 1:** Criar uma pasta de diretório para o projeto.
```
mkdir ~/projeto
```
---
- **Passo 2:** Clonar esse repositório que contém o arquivo **docker-compose.yml** :

```shell
git clone https://github.com/Rafael-Rueda/Replica-Set-Prototype.git
```

---
- **Passo 3:** Executar o comando para subir os containers no Docker.

```shell
docker-compose build --no-cache
```

```shell
docker-compose up -d
```

```shell
docker-compose exec mongo1 bash /scripts/init-replica.sh
```

## Comandos para debug e verificar o andamento da aplicação
- Verificar o Replica Set e todos os nós primários e secundários:
```shell
docker-compose exec mongo1 mongosh --eval "rs.status()"
```
---
- Parar um nó específico do Replica Set:
```shell
docker-compose stop mongo1
```
---
- Trocar o nó primário (forçar eleição de um novo líder):
```shell
docker-compose exec mongo1 mongosh --eval "rs.stepDown()"
```
---
## Configuração manual
### Configurar o banco de dados como Replica-Set, no container mongo1 MANUALMENTE (se necessário)

- **Passo 1:** Acessar o primeiro container mongo
```shell
docker exec -it mongo1 mongosh
```
---
- **Passo 2:** Inicializar um replica-set entre os containers
```shell
rs.initiate({
  _id: "rs0",
  members: [
    { _id: 0, host: "mongo1:27017" },
    { _id: 1, host: "mongo2:27017" },
    { _id: 2, host: "mongo3:27017" },
    { _id: 3, host: "mongo4:27017" }
  ]
})
```
---
- **Passo 3:** Verificar se está corretamente configurado
```shell
rs.status()
```
---

- OBS: Para sair do mongosh, `CTRL + C` ou `exit` no terminal.
### Configurar o container da aplicação MANUALMENTE (Se necessário)

```shell
docker exec -it posts_app bash
```

```shell
apt update && apt install -y curl unzip && apt install nano
```

```shell
curl -sS https://getcomposer.org/installer | php
```

```shell
mv composer.phar /usr/local/bin/composer
```

```shell
composer --version
```

```shell
pecl install mongodb
``` 
*Conforme a documentação: https://www.php.net/manual/en/mongodb.installation.php*

```shell
php --ini
```
*Ver onde está localizado esse arquivo de configuração, e edita-lo com o nano*:

```ini
;php.ini
extension=mongodb.so
```

```shell
service apache2 restart
```

```shell
php -m | grep mongodb
```

```shell
composer install
```
