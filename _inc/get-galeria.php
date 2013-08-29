<?php
    require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/_inc/config.php');

    $id = isset($_POST['id']) && is_numeric($_POST['id']) ? (integer) $_POST['id'] : FALSE;
    $tipo = isset($_POST['tipo']) && !empty($_POST['tipo']) ? $_POST['tipo'] : FALSE;
    
    if(!$id || !$tipo){
        echo 'error';
        die();
    }
    
    $galeria = Galeria::get_galeria($id, FALSE, FALSE, TRUE, TRUE);
    
    if($galeria){
        $galeria = $galeria[0];
        
        $categ = '';
        $titleAlt = htmlspecialchars($galeria->titulo);
        
        if(isset($galeria->categorias)){
            foreach($galeria->categorias as $item){
                $categ = '<a href="/categoria/' . $item['permalink'] . '">' . $item['titulo'] . '</a>, ';
            }
            $categ = substr($categ, 0, -2);
        }
        
        if($tipo == 'video'){
            echo <<< END
                <div class="itemGalDestaque">
                        <span>$galeria->data<br>$categ</span>
                        <br>
                        <h3>$galeria->titulo</h3>
                        <iframe title="$titleAlt" width="430" height="353" src="http://www.youtube.com/embed/$galeria->url?rel=0" frameborder="0" allowfullscreen></iframe>
                        <br>
                        <p>$galeria->descricao</p>
                </div>
END;
        } elseif($tipo == 'foto'){
            echo <<< END
                <div class="itemGalDestaque">
                    <span>$galeria->data<br>$categ</span>
                    <br>
                    <h3>$galeria->titulo</h3>
                    <img src="/upload/galeria/$galeria->url" alt="$titleAlt" />
                    <br>
                    <p>$galeria->descricao</p>
            </div>
END;
        } else {
            echo 'error';
            die();
        }
        
    }
    
?>