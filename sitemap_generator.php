<?php
$dsn = 'mysql:host=localhost;dbname=db_sorvetec_laravel';
$user = 'root';
$password = '';

$static_urls = [
  "https://www.sorvetec.com.br/",
  "https://www.sorvetec.com.br/produtos",
  "https://www.sorvetec.com.br/calculadora",
  "https://www.sorvetec.com.br/blog",
  "https://www.sorvetec.com.br/quem-somos",
  "https://www.sorvetec.com.br/produtos/3619/maquinas-para-comercio/maquinas-de-sorvetes/maquina-de-sorvete-na-chapa",
  "https://www.sorvetec.com.br/produtos/4015/maquinas-para-comercio/maquinas-de-sorvetes/maquina-de-sorvete-acai-e-frozen-yogurt-da-sorvetec-com-conservacao-noturna",
  "https://www.sorvetec.com.br/produtos/4079/maquinas-para-comercio/maquinas-de-sorvetes/maquina-de-sorvete-bql-825",
  "https://www.sorvetec.com.br/produtos/4242/maquina-de-sorvete-expresso-modelo-bql-825t",
  "https://www.sorvetec.com.br/produtos/4256/maquina-de-sorvete-na-chapa-de-mesa-sorvetec",
  "https://www.sorvetec.com.br/produtos/4272/maquina-de-sorvete-expresso-bql-818t-sorvetec",
  "https://www.sorvetec.com.br/produtos/6275/maquina-de-sorvete-expresso-de-piso-825b-sh",
  "https://www.sorvetec.com.br/produtos/6517/maquina-de-sorvete-na-chapa-de-mesa-compacta-usada",
  "https://www.sorvetec.com.br/produtos/7217/maquina-de-sorvete-expresso-825b-p-com-porta-casquinhas-sh"
];

try {
  $conexao = new PDO($dsn, $user, $password);

  //Consulta url dos posts
  $query = '
   select post_url, updated_at from posts
  ';

  $statement = $conexao->query($query);
  $posts_url = $statement->fetchAll(PDO::FETCH_ASSOC);

  // Gera o arquivo XML do sitemap
  $xml = '<?xml version="1.0" encoding="UTF-8"?>
    <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

  foreach ($static_urls as $url) {
    $xml .= "
      <url>
        <loc>$url</loc>
        <changefreq>weekly</changefreq>
      </url> ";
  };

  foreach ($posts_url as $url) {
    $datetime = new DateTime($url['updated_at']);
    $date = $datetime->format(DATE_W3C);
    $xml .= "
      <url>
        <loc>https://www.sorvetec.com.br/blog/{$url["post_url"]}</loc>
        <lastmod>$date</lastmod>
        <changefreq>weekly</changefreq>
      </url> ";
  };
  $xml .=
    '</urlset>';

  // Abre ou cria o arquivo sitemap caso não exista
  $arquivo = fopen('sitemap.xml', 'w');
  if (fwrite($arquivo, $xml)) {
    echo "Arquivo sitemap.xml criado com sucesso";
  } else {
    echo "Não foi possível criar o arquivo. Verifique as permissões do diretório.";
  };
  fclose($arquivo);
} catch (PDOException $error) {
  echo "Error: {$error->getCode()} Mensagem: {$error->getMessage()}";
}