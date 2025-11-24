<?php

    Class CategoryController {
        
        private $categoryModel;

        function __construct(CategoryModel $categoryModel){
            $this->categoryModel = $categoryModel;
        }

        function addCategory($name) {
            $this->categoryModel->addCategory($name);
        }

        function linkCategory($livro_id, $categoria_id){
            $this->categoryModel->linkCategory($livro_id, $categoria_id);
        }

        function removeCategory($categoria_id){
            $this->categoryModel->removeCategory( $categoria_id);
        }

        function removeCategoryLink($livro_id, $categoria_id){
            $this->categoryModel->linkCategory($livro_id, $categoria_id);
        }

        function getAllCategories(){
            return $this->categoryModel->getAllCategories();
        }

        function getAllCategoriesLink(){
            return $this->categoryModel->getAllCategoriesLink();
        }

    }

?>