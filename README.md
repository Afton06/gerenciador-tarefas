# Gerenciador de Tarefas 📝

## 📌 Descrição
Este projeto é um **sistema simples de Gerenciamento de Tarefas** desenvolvido em **PHP orientado a objetos** com **MySQL**.  
Ele permite que cada usuário faça login, crie e organize suas tarefas em categorias, edite, conclua e exclua atividades.

## 🚀 Funcionalidades
- Registro e login de usuários
- Adicionar, editar e excluir tarefas
- Filtrar tarefas por status (pendente / concluída)
- Categorizar tarefas
- Controle de sessões com logout
- Validação (descrição limitada a 20 caracteres)
- Interface amigável com CSS personalizado

## 🛠️ Tecnologias Utilizadas
- PHP 8+
- MySQL
- PDO (PHP Data Objects)
- HTML5 + CSS3
- XAMPP (para ambiente local)

## 🗂️ Estrutura do Projeto
gerenciador_tarefas/
│── conexao.php
│── index.php
│── adicionar.php
│── editar.php
│── excluir.php
│── login.php
│── register.php
│── logout.php
│── style.css
│── Usuario.php
│── Tarefa.php
│── Categoria.php


## 🗄️ Modelagem do Banco de Dados
O sistema utiliza **3 tabelas principais**: `usuarios`, `tarefas`, `categorias`.

- Um **usuário** pode ter várias tarefas.
- Uma **categoria** pode estar em várias tarefas.
- Cada **tarefa** pertence a 1 usuário e 1 categoria.

### DER (Diagrama Entidade Relacionamento)
![DER](DER_gerenciador_tarefas.png)

## 📥 Instalação
1. Clone este repositório:
   ```bash
   git clone https://github.com/SEU_USUARIO/gerenciador_tarefas.git
   
2. Importe o banco de dados:
   Abra o DBeaver ou phpMyAdmin
   Execute o script SQL disponível no arquivo banco.sql

3. Configure a conexão no arquivo conexao.php (usuário e senha do MySQL).

4. Inicie o servidor Apache/MySQL no XAMPP.

5. Acesse no navegador:
   http://localhost/gerenciador_tarefas

👨‍💻 Usuário Padrão
Email: admin@teste.com
Senha: 123456

👥 feito por:
Ronald Pereira Vernek

📌 Desenvolvido para fins acadêmicos — Projeto avaliativo de PHP com OOP + MySQL
