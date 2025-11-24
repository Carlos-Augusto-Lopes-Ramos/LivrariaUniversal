<?php 

class CategoryModel
{
    private CategoriesDTO $categoriesDTO;

    public function __construct(CategoriesDTO $categoriesDTO)
    {
        $this->categoriesDTO = $categoriesDTO;
    }

    public function addCategory($name) {
        return $this->categoriesDTO->createCategory($name);
    }

    public function linkCategory($livroId, $categoria_id){
        return $this->categoriesDTO->createCategoryLink($livroId, $categoria_id);
    }

    public function removeCategory($categoryId) {
        return $this->categoriesDTO->removeCategory($categoryId);
    }

    public function removeCategoryLink($livroId, $categoria_id)
    {
        return $this->categoriesDTO->removeCategoryLink($livroId, $categoria_id);
    }

    public function getAllCategories() {
        return $this->categoriesDTO->getAllCategories();
    }
    public function getAllCategoriesLink() {
        return $this->categoriesDTO->getAllCategoriesLinks();
    }

}


?>