    <?php
    //On clear le terminal
    system('clear');
    //On définit notre directory

    //On définit nos options
    //Options courte
    $short_opts = ("ri:s:p:o:c:");
    //Options longue
    $long_opts = array('recursive', 'output-image:', 'output-style:', 'padding:', 'override:', 'columns_number:');
    //Le tableau qui contient toutes nos options
    $options=getopt($short_opts, $long_opts);
    if($options['c'] == 0){
      echo "\033[31mSorry, you need to use at least 1 column\n";
      return FALSE;
    }
    $directory = is_dir(end($argv)) ? end($argv) : $directory = '.';
    if(!is_dir(end($argv))) {
      echo "\033[34mWarning : no directory detected, the sprite has been generated from the current directory.\n";
    }
    //Options : Recursive
    $recursive = array_key_exists('r', $options) || array_key_exists('recursive', $options) ? true : false;
    //Options : Nom de l'image
    if(array_key_exists('i', $options) || array_key_exists('output-image', $options)) {
      $output_name = array_key_exists('i', $options) ? $options['i'] : $options['output-image'];
    } else {
      $output_name = "sprite.png";
    }
    //Options : Nom du css
    if(array_key_exists('s', $options) || array_key_exists('output-style', $options)) {
      $output_style = array_key_exists('s', $options) ? $options['s'] : $options['output_style'];
    } else {
      $output_style = "sprite.css";
    }
    //Options : Padding
    if(array_key_exists('p', $options) || array_key_exists('padding', $options)) {
      $padding = array_key_exists('p', $options) ? $options['p'] : $options['padding'];
    } else {
      $padding = 0;
    }
    //Options : Colonnes
    if(array_key_exists('c', $options) || array_key_exists('columns_number', $options)) {
      $column = array_key_exists('c', $options) ? $options['c'] : $options['columns_number'];
    } else {
      $column = 0;
    }
      // On supprime l'image avec le nom par défaut
    !file_exists("sprite.png") ? : unlink("sprite.png");
    // On supprime l'image qui a le même nom que l'output
    !file_exists($output_name) ? : unlink($output_name);
    // On liste les tout les fichiers dans le dossier et sous-dossier si demandé et on ajoute les images dans un tableau
    function list_paths($directory){
      global $recursive;
      static $tableau=array();
      $glob = glob($directory . '/*');
      foreach ($glob as $fichier){
        if (is_dir($fichier)){
          if($recursive) {
            list_paths($fichier);
          }
        }
        elseif(mime_content_type($fichier)=="image/png" || mime_content_type($fichier)=="image/jpeg" || mime_content_type($fichier)=="image/gif" || mime_content_type($fichier)=="image/webp"){
          array_push($tableau,$fichier);
        } else {echo "\033[31mYour file $fichier has been skipped because it's not an image !\033[0m\n";
        }
      }
      return $tableau;
    }
    $tableau = list_paths($directory);
    //On commence notre fonction pour générer le background
    function css_generator(){
      global $tableau, $recursive, $output_name, $output_style, $padding, $options;
      $cnt_img = count($tableau);
      // On créé un fichier sprite si un fichier porte le même nom il sera overwrité
      touch($output_style);
      $sprite_css = "";
      //On définit une variable X qu'on utilisera plus tard
      $size_x_total = 0;
    if(array_key_exists('c', $options)){
      $options['columns_number'] = $options['c'];
    } elseif(array_key_exists('columns_number', $options)){
      $options['c'] = $options['columns_number'];
    } else {
      $options['c'] = $cnt_img;
    }
    if(array_key_exists('p', $options)){
      $options['padding'] = $options['p'];
    } elseif(array_key_exists('padding', $options)){
      $options['p'] = $options['padding'];
    } else {
      $options['p'] = 0;
    }
    if(array_key_exists('o', $options)){
      $options['override'] = $options['o'];
    } elseif(array_key_exists('override', $options)){
      $options['o'] = $options['override'];
    }
    if(array_key_exists('c', $options))  {
      // On ajoute une condition au cas ou notre utilisateur veut plus de colonnes que d'images présente
      if($options['c'] > $cnt_img){
        $options['c'] = $cnt_img;
      }
      //On calcule notre nombre maximal de ligne à l'arrondi supérieur
      $max_lines = ceil($cnt_img/$options['c']);
      //On définit un compteur $o qui sera la key dans notre tableau d'image
      $o = "0";
      //On définit un compteur h qui sera le compteur de ligne
      $h = "0";
      $total_column_x = 0;
      $total_column_y = 0;
      for ($i=0; $i < $max_lines ; $i++) {
        $column_x = 0;
        $column_y = 0;
       for ($gg=1; $gg <= $options['c'] ; $gg++) {
         if(array_key_exists($o, $tableau)){
           // On récupère la tailles images sous forme de tableau qu'on ajoute à notre tableau $size
          $size = getimagesize($tableau[$o]);
           // On extrait de ce tableau les keys [0] et [1] qui correspondent à notre axe X et Y
           if(array_key_exists('o', $options)){
             $size_x = $options['o'];
             $size_y = $options['o'];
           } else {
             [$size_x, $size_y] = $size;
           }
           // On ajout la largeur de chaque image entre elle à fin de connaitre la largeur de notr background
         }
         $o++;
         $column_x +=$size_x;
       $array_y[]= $size_y;
       $array_x[]= $size_x;
      }
      $max_column_x[] = $column_x;
      $column_y = max($array_y);
      $max_column_xx = max($max_column_x);
      $total_column_x += $column_x;
      $total_column_y += $column_y;
       $h++;
       $column_1[]= $column_y;
      }
    if(($options['c'] == 1 )){
      $total_column_y = max($column_1);
      $max_column_xx = $size_x * $cnt_img + ($padding*($cnt_img-1));
    }
    // On doit récupérer le plus gros X
    $paddingX = $paddingY = $padding;
    }
    $max_column_xx = $max_column_xx + ($padding*($options['c']+1));
    $total_column_y = $total_column_y + ($padding*(2));
      $l = 0;
      // On créé le background
      // On va récupérer toutes les infos des images passé en argument
      for ($i=0; $i < $cnt_img ; $i++) {
        // On récupère la tailles images sous forme de tableau qu'on ajoute à notre tableau $size
        $size = getimagesize($tableau[$i]);
        // On extrait de ce tableau les keys [0] et [1] qui correspondent à notre axe X et Y
        [$size_x, $size_y] = [$size[0], $size[1]];
        // On ajout la largeur de chaque image entre elle à fin de connaitre la largeur de notr background
        $size_x_total += $size_x;
        // On vérifie si notre paramètre qui redimentionne est présent ou pas à fin de passer les bonnes dimenssion à notre background
        if(array_key_exists('o', $options) || array_key_exists('override', $options))  {
          $overrideX = (array_key_exists('o', $options) ? $options['o'] : $options['override']) ;
          $overrideY = (array_key_exists('o', $options) ? $options['o'] : $options['override']) ;
        } else {
          $overrideX = $size_x;
          $overrideY = $size_y;
        }
        //On ajoute chaque hauteur dans un tableau, on en fait un second pour la largeur
        [$array_y[], $array_x[]] = [$size_y, $size_x];
      }
      //On récupère la plus grande hauteur parmis toutes les images du tableau
      $size_y_total = max($array_y);
      //On créait notre background en prenant en compte le padding ainsi que la taille finale des images
        //New Background Generation
        $BackgroundX = 0;
        $BackgroundY = 0;
        $NewLine = 1;
        $TotalBackgroundY = 0;
        $ArrayTotalBackgroundX = [];
        for ($i=0; $i < $cnt_img; $i++) {
        $BackgroundVarSize = getimagesize($tableau[$i]);
        if(array_key_exists('o', $options)){
          $ImageBackgroundX = $options['o'];
          $ImageBackgroundY = $options['o'];
        } else {
          $ImageBackgroundX = $BackgroundVarSize[0];
          $ImageBackgroundY = $BackgroundVarSize[1];
        }
        $BackgroundX += $ImageBackgroundX;
        $BackgroundVarY[] = $ImageBackgroundY;
        $BackgroundY = max($BackgroundVarY);
        if ($NewLine == $options['c'] || $i == ($cnt_img -1)) {
          $NewLine = 0;
          $TotalBackgroundY += $BackgroundY;
          $ArrayTotalBackgroundX[] = $BackgroundX;
          $BackgroundX = 0;
          $BackgroundY = 0;
          $BackgroundVarY = [];
        }
        $NewLine++;
      }
      $TotalBackgroundY = $TotalBackgroundY + ($padding*($max_lines+1));
      $TotalBackgroundX = max($ArrayTotalBackgroundX) + ($padding*($options['c']+1));        $Background = imagecreatetruecolor($TotalBackgroundX, $TotalBackgroundY );
      imagealphablending($Background, false);
      imagesavealpha($Background, true);
      //ÉTAPE DEUX COLLER LES IMAGES SUR LE BACKGROUND
      $ligne = 0;
      for ($ii=0; $ii < $cnt_img ; $ii++) {
        $size = getimagesize($tableau[$ii]);
        [$size_x, $size_y] = [$size[0], $size[1]];
        if(!array_key_exists('o', $options)){
          $overrideX = $size_x;
          $overrideY = $size_y;
        }
        $image = imagecreatefromstring(file_get_contents($tableau[$ii]));
        imagecopyresized($Background, $image, $paddingX, $paddingY , 0, 0, $overrideX, $overrideY, $size_x, $size_y);
        $paddingX += $overrideX + $padding;
        $ligne++;
        $SizeCopyY[] = $overrideY;
        if ($ligne == $options['c']) {
          $ligne = 0;
          $paddingX = $options['p'];
          $paddingY += max($SizeCopyY) + $padding;
          $SizeCopyY = [];
        }
        $sprite_css .= " /* File number $ii Height: $overrideX Width: $overrideY */
        .replace$ii {
          width: {$overrideX}px;
          height: {$overrideY}px;
          background: url($output_name) $paddingX $paddingY;
        }\n\n";
      }
      file_put_contents($output_style, $sprite_css);
    imagepng($Background, $output_name); // On sauvegarde l'image final
    echo "\033[32mYour sprite and your css have been generated in your folder !\033[0m\n"; //Message de succés
    }
    css_generator();
