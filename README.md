## Instituição: SEDESE
#### Unidade responsável: Diretoria de Gestão Documental, Informação e Tecnologia (DGDIT).

##

### Versões:

- PHP: 8.4.21
- MySql: 5.7.11
- Laravel: 13.12.0
- NodeJs: 18x
- Composer: 2.9.5

##
### Como funciona o versionamento do sistema:

<!-- Badges coloridos via shields.io -->
![MAJOR](https://img.shields.io/badge/MAJOR-1.x.x-red) ![MINOR](https://img.shields.io/badge/MINOR-x.5.x-blue) ![PATCH](https://img.shields.io/badge/PATCH-x.x.2-brightgreen)


- <strong>MAJOR (1.x.x)</strong> → Mudanças grandes, que podem quebrar compatibilidade com versões anteriores.  
  ###### Ex: trocar toda a interface, mudar API, remover recursos antigos.

- <strong>MINOR (x.5.x)</strong> → Novas funcionalidades compatíveis com as anteriores.  
  ###### Ex: adicionar um novo recurso, mas sem quebrar o que já existe.

- <strong>PATCH (x.x.2)</strong> → Correções pequenas ou melhorias de bug sem adicionar recurso novo.  
  ###### Ex: corrigir erro de CSS, arrumar um bug no login.

  ##

  ###### Caso o número de patch fique muito alto, pode incrementar o MINOR e zerar o PATCH, pra manter o histórico mais limpo.
  ###### Ex: v1.5.99 → v1.6.0

##
### Como rodar local:
###### Copie e cole o .env.exemple e renomeie para .env, depois altere os dados para o seu banco de dados local.

```bash 
npm install && composer install

```
```bash
php artisan vendor:publish && php artisan migrate
```
```bash
php artisan key:generate && php artisan db:seed --class=YourTableSeeder
```
```bash
npm run dev && php artisan serve
```
##
### Comandos para rodar testes basicos CRUD/Auth

```bash
php artisan view:clear && php artisan test
```
