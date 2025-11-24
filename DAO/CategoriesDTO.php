<?php

    Class CategoriesDTO {

        private $con;

        public function __construct(PDO $pdo) {
            $this->con = $pdo;
        }

        public function createCategory($name) {
            $sql = "INSERT INTO categorias (nome) VALUES (:nome)";
        $stmt = $this->con->prepare($sql);
        $stmt->bindParam(':nome', $name);
        return $stmt->execute();
        }

        public function createCategoryLink($livro_id, $categoria_id) {
            $sql = "INSERT INTO livro_categoria (livro_id, categoria_id) VALUES (:livro_id, :categoria_id)";
        $stmt = $this->con->prepare($sql);
        $stmt->bindParam(':livro_id', $livro_id);
        $stmt->bindParam(':categoria_id', $categoria_id);
        return $stmt->execute();
        }
        public function removeCategory($categoria_id){
            $sql= "DELETE FROM livro_categoria WHERE categoria_id = :categoriaId; ";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(':categoriaId', $categoria_id);
            $stmt->execute();
            $sql= "DELETE FROM categorias WHERE categoria_id = :categoriaId; ";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(':categoriaId', $categoria_id);
            return $stmt->execute();
        }
        public function removeCategoryLink($livro_id, $categoriaId)
        {
            $sql= "DELETE FROM livro_categoria WHERE categoria_id = :categoriaId AND livro_id = :livro_id;";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(':categoriaId', $categoriaId);
            $stmt->bindParam(':livro_id', $livro_id);
            return $stmt->execute();
        }

        public function getAllCategories()
        {
            $sql = "SELECT * FROM categorias;";
            $stmt = $this->con->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getAllCategoriesLinks()
        {
            $sql = "SELECT * FROM livro_categoria;";
            $stmt = $this->con->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }

?>