<?php

class BookDTO
{
    private $con;

    public function __construct(PDO $pDO)
    {
        $this->con = $pDO;
    }

    public function addBook($title, $description, $price, $isbn, $imageFile, $filePdf)
    {
        $image = "";
        $pdf = "";

        // Configurações de upload para imagens
        $diretorioImagens = './assets/images/';
        $tamanhoMaximoImagem = 2 * 1024 * 1024; // 2MB
        $extensoesImagensPermitidas = ['jpg', 'jpeg', 'png', 'gif'];

        // Configurações de upload para PDFs
        $diretorioPDF = './assets/pdfs/';
        $tamanhoMaximoPDF = 20 * 1024 * 1024; // 20MB
        $extensaoPDFPermitida = 'pdf';

        // Verifica se o diretório de imagens existe ou tenta criá-lo
        if (!is_dir($diretorioImagens)) {
            mkdir($diretorioImagens, 0755, true);
        }

        // Verifica se o diretório de arquivos existe ou tenta criá-lo
        if (!is_dir($diretorioPDF)) {
            mkdir($diretorioPDF, 0755, true);
        }

        // Processa o upload da imagem
        if (isset($imageFile) && $imageFile['error'] === UPLOAD_ERR_OK) {
            $arquivoTmp = $imageFile['tmp_name'];
            $nomeOriginal = basename($imageFile['name']);
            $tamanhoArquivo = $imageFile['size'];

            // Validações de imagem
            $extensao = strtolower(pathinfo($nomeOriginal, PATHINFO_EXTENSION));
            if ($tamanhoArquivo > $tamanhoMaximoImagem) {
                header('Location: ./admin/books.php?error=O arquivo de imagem excede o tamanho máximo permitido.');
                exit;
            }

            if (!in_array($extensao, $extensoesImagensPermitidas)) {
                header('Location: ./admin/books.php?error=Extensão de imagem não permitida.');
                exit;
            }

            $novoNomeImagem = uniqid('img_', true) . '.' . $extensao;
            $image = $novoNomeImagem;
            $caminhoDestinoImagem = $diretorioImagens . $novoNomeImagem;

            if (!move_uploaded_file($arquivoTmp, $caminhoDestinoImagem)) {
                header('Location: ./admin/books.php?error=Falha ao salvar a imagem.');
                exit;
            }
        } else {
            header('Location: ./admin/books.php?error=Imagem não enviada!');
            exit;
        }

        // Processa o upload do arquivo PDF
        if (isset($filePdf) && $filePdf['error'] === UPLOAD_ERR_OK) {
            $arquivoTmpPDF = $filePdf['tmp_name'];
            $nomeOriginalPDF = basename($filePdf['name']);
            $tamanhoArquivoPDF = $filePdf['size'];

            // Validações do PDF
            $extensaoPDF = strtolower(pathinfo($nomeOriginalPDF, PATHINFO_EXTENSION));
            if ($tamanhoArquivoPDF > $tamanhoMaximoPDF) {
                header('Location: ./admin/books.php?error=O arquivo PDF excede o tamanho máximo permitido.');
                exit;
            }

            if ($extensaoPDF !== $extensaoPDFPermitida) {
                header('Location: ./admin/books.php?error=Apenas arquivos PDF são permitidos.');
                exit;
            }

            // Valida o tipo MIME
            $tipoMimePDF = mime_content_type($arquivoTmpPDF);
            if ($tipoMimePDF !== 'application/pdf') {
                header('Location: ./admin/books.php?error=O arquivo enviado não é um PDF válido.');
                exit;
            }

            $novoNomePDF = uniqid('pdf_', true) . '.' . $extensaoPDF;
            $pdf = $novoNomePDF;
            $caminhoDestinoPDF = $diretorioPDF . $novoNomePDF;

            if (!move_uploaded_file($arquivoTmpPDF, $caminhoDestinoPDF)) {
                header('Location: ./admin/books.php?error=Falha ao salvar o arquivo PDF.');
                exit;
            }
        } else {
            header('Location: ./admin/books.php?error=PDF não enviado!');
            exit;
        }

        // Insere no banco de dados
        $sql = "INSERT INTO books (title, description, price, isbn, image, file) 
            VALUES (:title, :description, :price, :isbn, :image, :file)";
        $stmt = $this->con->prepare($sql);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':isbn', $isbn);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':file', $pdf); // Aqui o campo é o caminho do arquivo PDF
        $stmt->execute();
    }

    public function getAllActiveBooks()
{
    $sql = "SELECT 
                books.*,
                COALESCE(GROUP_CONCAT(authors.name SEPARATOR ', '), 'Autor desconhecido') AS author,
                COUNT(purchases.id) AS total_vendas
            FROM 
                books
            LEFT JOIN 
                book_author ON books.id = book_author.id_book
            LEFT JOIN 
                authors ON book_author.id_author = authors.id
            LEFT JOIN
                purchases ON purchases.id_book = books.id
            WHERE 
                books.is_active = 1
            GROUP BY 
                books.id;
            ";

    $stmt = $this->con->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    public function getAllActiveBooksWhoutAuthors()
    {
        $sql = "SELECT * FROM books WHERE is_active = 1";
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $books;
    }

    public function getAllBooks()
    {
        $sql = "SELECT 
                    books.*,
                    COALESCE(GROUP_CONCAT(authors.name SEPARATOR ', '), 'Autor desconhecido') as author
                FROM 
                    book_author
                INNER JOIN 
                    books ON book_author.id_book = books.id
                INNER JOIN 
                    authors ON book_author.id_author = authors.id
                GROUP BY 
                    books.id;";
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $books;
    }
    public function getBookById($id)
    {
        $sql = "SELECT 
                    books.*,
                    COALESCE(GROUP_CONCAT(authors.name SEPARATOR ', '), 'Autor desconhecido') as author
                FROM 
                    books
                LEFT JOIN 
                    book_author ON book_author.id_book = books.id
                LEFT JOIN 
                    authors ON book_author.id_author = authors.id
                WHERE 
                    books.id = :id AND books.is_active = 1
                GROUP BY 
                    books.id;";
        $stmt = $this->con->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $book = $stmt->fetch(PDO::FETCH_ASSOC);
        return $book;
    }
    public function getAllBooksByUserId($id)
    {
        $sql = "SELECT 
                    books.*,
                    COALESCE(GROUP_CONCAT(authors.name SEPARATOR ', '), 'Autor desconhecido') AS author
                FROM 
                    purchases
                INNER JOIN 
                    books ON purchases.id_book = books.id
                LEFT JOIN 
                    book_author ON books.id = book_author.id_book
                LEFT JOIN 
                    authors ON book_author.id_author = authors.id
                WHERE 
                    purchases.id_user = :id 
                    AND books.is_active = 1
                GROUP BY 
                    books.id;
                ";
        $stmt = $this->con->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $books;
    }

    public function getAllBooksByUserIdLikeTitle($id, $title)
    {
        $sql = "SELECT 
                    books.*,
                    COALESCE(GROUP_CONCAT(authors.name SEPARATOR ', '), 'Autor desconhecido') AS author
                FROM 
                    purchases
                INNER JOIN 
                    books ON purchases.id_book = books.id
                LEFT JOIN 
                    book_author ON books.id = book_author.id_book
                LEFT JOIN 
                    authors ON book_author.id_author = authors.id
                WHERE 
                    purchases.id_user = :id 
                    AND books.is_active = 1 
                    AND books.title LIKE :title
                GROUP BY 
                    books.id;
                ";
        $stmt = $this->con->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindValue(':title', '%' . $title . '%');
        $stmt->execute();
        $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $books;
    }

    public function deleteBook($id)
    {
        $sql = "UPDATE books SET is_active = 0 WHERE id = :id";
        $stmt = $this->con->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }
    public function updateBook($id, $title, $description, $price, $isbn, $imageFile = null, $filePdf = null)
    {
        $image = "";
        $pdf = "";

        // Configurações de upload para imagens
        $diretorioImagens = './assets/images/';
        $tamanhoMaximoImagem = 2 * 1024 * 1024; // 2MB
        $extensoesImagensPermitidas = ['jpg', 'jpeg', 'png', 'gif'];

        // Configurações de upload para PDFs
        $diretorioPDF = './assets/pdfs/';
        $tamanhoMaximoPDF = 5 * 1024 * 1024; // 5MB
        $extensaoPDFPermitida = 'pdf';

        // Verifica e cria diretórios, se necessário
        if (!is_dir($diretorioImagens)) {
            mkdir($diretorioImagens, 0755, true);
        }

        if (!is_dir($diretorioPDF)) {
            mkdir($diretorioPDF, 0755, true);
        }

        // Processa o upload da nova imagem, se fornecida
        if ($imageFile && $imageFile['error'] === UPLOAD_ERR_OK) {
            $arquivoTmp = $imageFile['tmp_name'];
            $nomeOriginal = basename($imageFile['name']);
            $tamanhoArquivo = $imageFile['size'];

            $extensao = strtolower(pathinfo($nomeOriginal, PATHINFO_EXTENSION));
            if ($tamanhoArquivo > $tamanhoMaximoImagem) {
                header('Location: ./admin/books.php?error=O arquivo de imagem excede o tamanho máximo permitido.');
                exit;
            }

            if (!in_array($extensao, $extensoesImagensPermitidas)) {
                header('Location: ./admin/books.php?error=Extensão de imagem não permitida.');
                exit;
            }

            $novoNomeImagem = uniqid('img_', true) . '.' . $extensao;
            $image = $novoNomeImagem;
            $caminhoDestinoImagem = $diretorioImagens . $novoNomeImagem;

            if (!move_uploaded_file($arquivoTmp, $caminhoDestinoImagem)) {
                header('Location: ./admin/books.php?error=Falha ao salvar a imagem.');
                exit;
            }
        }

        // Processa o upload do novo PDF, se fornecido
        if ($filePdf && $filePdf['error'] === UPLOAD_ERR_OK) {
            $arquivoTmpPDF = $filePdf['tmp_name'];
            $nomeOriginalPDF = basename($filePdf['name']);
            $tamanhoArquivoPDF = $filePdf['size'];

            $extensaoPDF = strtolower(pathinfo($nomeOriginalPDF, PATHINFO_EXTENSION));
            if ($tamanhoArquivoPDF > $tamanhoMaximoPDF) {
                header('Location: ./admin/books.php?error=O arquivo PDF excede o tamanho máximo permitido.');
                exit;
            }

            if ($extensaoPDF !== $extensaoPDFPermitida) {
                header('Location: ./admin/books.php?error=Apenas arquivos PDF são permitidos.');
                exit;
            }

            $tipoMimePDF = mime_content_type($arquivoTmpPDF);
            if ($tipoMimePDF !== 'application/pdf') {
                header('Location: ./admin/books.php?error=O arquivo enviado não é um PDF válido.');
                exit;
            }

            $novoNomePDF = uniqid('pdf_', true) . '.' . $extensaoPDF;
            $pdf = $novoNomePDF;
            $caminhoDestinoPDF = $diretorioPDF . $novoNomePDF;

            if (!move_uploaded_file($arquivoTmpPDF, $caminhoDestinoPDF)) {
                header('Location: ./admin/books.php?error=Falha ao salvar o arquivo PDF.');
                exit;
            }
        }

        // Monta o SQL de atualização
        $sql = "UPDATE books 
            SET title = :title, 
                description = :description, 
                price = :price, 
                isbn = :isbn";

        // Adiciona as colunas de arquivo se novos arquivos forem enviados
        if ($image) {
            $sql .= ", image = :image";
        }
        if ($pdf) {
            $sql .= ", file = :file";
        }

        $sql .= " WHERE id = :id";

        $stmt = $this->con->prepare($sql);

        // Liga os parâmetros ao SQL
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':isbn', $isbn);
        if ($image) {
            $stmt->bindParam(':image', $image);
        }
        if ($pdf) {
            $stmt->bindParam(':file', $pdf);
        }
        $stmt->bindParam(':id', $id);

        $stmt->execute();

        header('Location: ./admin/books.php?success=Livro atualizado com sucesso!');
    }

    public function getBookLikeTitle($title)
    {
        $sql = "SELECT 
                    books.*,
                    COALESCE(GROUP_CONCAT(authors.name SEPARATOR ', '), 'Autor desconhecido') as author
                FROM 
                    book_author
                INNER JOIN 
                    books ON book_author.id_book = books.id
                INNER JOIN 
                    authors ON book_author.id_author = authors.id
                WHERE books.title LIKE :title AND books.is_active = 1
                GROUP BY 
                    books.id;";
        $stmt = $this->con->prepare($sql);
        $stmt->bindValue(':title', '%' . $title . '%');
        $stmt->execute();
        $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $books;
    }
    public function isBookUser($id, $id_user)
    {
        $sql = "SELECT * FROM purchases WHERE id_book = :id AND id_user = :id_user";
        $stmt = $this->con->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':id_user', $id_user);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function getAllPurchasedBooks()
    {
        $sql = "SELECT 
            books.title AS book_title,
            users.name AS user_name,
            purchases.date,
            purchases.price
        FROM 
            purchases
        INNER JOIN 
            books ON purchases.id_book = books.id
        INNER JOIN 
            users ON purchases.id_user = users.id
        ORDER BY 
            purchases.date DESC;
        ";
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $books;
    }

    public function getBookQuantity() {

        $sql = 
        "SELECT id_book, COUNT(*) AS total_vendas
                FROM purchase
                GROUP BY id_book;
        ";
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $books;
    }
}
