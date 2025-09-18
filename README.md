# Gerenciador de Tarefas ✅

Um sistema simples de gerenciamento de tarefas desenvolvido em **PHP com orientação a objetos** e **MySQL**, como projeto avaliativo da disciplina.

---

## 🎯 Propósito

O objetivo deste sistema é aplicar os conceitos de **Programação Orientada a Objetos (POO)**, **modelagem de banco de dados** e **boas práticas de desenvolvimento em PHP**, criando uma aplicação funcional e navegável para gerenciamento de tarefas.

---

## 🛠️ Tecnologias utilizadas
- **PHP 8+**
- **MySQL (MariaDB via XAMPP)**
- **HTML5 / CSS3**
- **XAMPP** (servidor local Apache + MySQL)
- **Composer** (para gerenciar dependências externas, ex: biblioteca de segurança de senhas)

---

## 📂 Estrutura do Projeto

gerenciador_tarefas/
│── index.php # Página inicial com lista e cadastro de tarefas
│── login.php # Login de usuário
│── logout.php # Logout de usuário
│── adicionar.php # Cadastro de tarefa
│── editar.php # Edição de tarefa
│── excluir.php # Exclusão de tarefa
│── categorias.php # Cadastro e exclusão de categorias
│── conexao.php # Classe de conexão com banco de dados (PDO)
│── style.css # Estilos básicos
│── README.md # Documentação do projeto

---

## 🗄️ Banco de Dados

### DER (Diagrama Entidade-Relacionamento)
🔗 [Link para o DER](https://exemplo.com/der.png) *(adicione a imagem exportada do DBeaver ou Draw.io)*

### Script SQL de Criação

```sql
CREATE DATABASE gerenciador;
USE gerenciador;

-- Usuários
CREATE TABLE usuarios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL
);

-- Categorias
CREATE TABLE categorias (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL
);

-- Tarefas
CREATE TABLE tarefas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    titulo VARCHAR(255) NOT NULL,
    descricao TEXT,
    status ENUM('pendente','concluida') DEFAULT 'pendente',
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_vencimento DATE NULL,
    id_categoria INT,
    FOREIGN KEY (id_categoria) REFERENCES categorias(id)
);

INSERT INTO usuarios (nome, email, senha) 
VALUES ('Admin', 'admin@teste.com', '123456');

