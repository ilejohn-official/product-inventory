<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products Page</title>
</head>
<body>
  <center>
        <h1> Welcome to the first page</h1>

        <div>
            <table></table>
          <?php echo count($products); ?>
        </div>

     <table class="table">
        <thead class="thead-light">
          <tr>
            <th scope="col">SKU</th>
            <th scope="col">Name</th>
            <th scope="col">Price</th>
            <th scope="col">Attribue</th>
          </tr>
        </thead>
     <?php foreach($products as $product){
         
      echo "<tbody>
            <td>". $product->sku ."</td>
            <td>". $product->name ."</td>
            <td>$". $product->price ."</td>
            <td>".
             json_decode($product->attribute, true)['key'] .": " .json_decode($product->attribute, true)['value']. ".
             ".json_decode($product->attribute, true)['unit']
            ."</td>
        </tbody>";
       } ?>
    </table>
  </center>
</body>
</html>
