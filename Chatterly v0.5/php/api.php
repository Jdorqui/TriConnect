<?php
if (count($amigos) > 0) 
{
    foreach ($amigos as $amigo) 
    {
        $amigoDir = "../assets/users/{$amigo['username']}/img_profile/";
        $defaultImage = '../assets/imgs/default_profile.png';

        $amigoImages = glob($amigoDir . '*.{jpg,jpeg,png}', GLOB_BRACE); //glob — busca coincidencias de nombres de ruta de acuerdo a un patrón por tanto busca las imagenes en la carpeta del amigo y las guarda en un array para luego ordenarlas por fecha de modificacion y mostrar la mas reciente

        if (!empty($amigoImages)) //si hay imagenes en la carpeta del amigo
        {
            usort($amigoImages, function ($a, $b) //usort — ordena un array según sus valores usando una función de comparación definida por el usuario  y se ordenan las imagenes por fecha de modificacion 
            {
                return filemtime($b) - filemtime($a); //filemtime — obtiene la fecha de modificación de un archivo y se ordenan las imagenes por fecha de modificacion 
            });

            $foto = $amigoImages[0]; //se guarda la imagen mas reciente
        } 
        else 
        {
            $foto = $defaultImage; //si no hay imagenes se muestra la imagen por defecto
        }

        $destinatario = ($amigo['id_user1'] == $id_usuario_actual) ? $amigo['id_user2'] : $amigo['id_user1']; //se obtiene el id del amigo
    }
}
?>