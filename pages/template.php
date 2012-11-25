<?php #charset utf-8

/**
Gestion des templates avec remplacement iteratif, récursif et alternatif

@author  : Batiste Bieler
@company : http://dosimple.ch
@version : 0.3

Copyright (C) 2004  Bieler Batiste

This library is free software; you can redistribute it and/or
modify it under the terms of the GNU Lesser General Public
License as published by the Free Software Foundation; either
version 2.1 of the License, or (at your option) any later version.

This library is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public
License along with this library; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

class Template{
    
    //var $model;
    //var $contextArray;
    
    function Template( $model )
    {    
        $fp = fopen( $model, 'r' );
        $this->model = fread( $fp, filesize($model) );
        fclose($fp);
        $this->contextArray=array();
    }
    
    // remplacement simple
    function replace( $tagName, $contents )
    {
        $this->model = preg_replace("#<!--%$tagName%-->#si", $contents, $this->model );
    }
    
    // remplacement iteratif
    function iterateReplace( $iterateName, $contentsArray )
    {
        if( preg_match("#<!--%loop:$iterateName%-->(.*?)<!--%endloop:$iterateName%-->#si", $this->model, $matches ) )
        {
            $iterateContent = $matches[1];
            $result='';
            foreach( $contentsArray as $contents )
            {
                $iterateContentBuffer = $this->solveAlternative( $iterateContent, $contents );
                foreach( $contents as $tag=>$content )
                {
                    $iterateContentBuffer = preg_replace("#<!--% ?$tag ?%-->#si", $content, $iterateContentBuffer );
                }
                $result.=$iterateContentBuffer;
            }
            $this->model = preg_replace("#<!--%loop:$iterateName ?%-->.*?<!--%endloop:$iterateName%-->#si", $result, $this->model );
        }
        else
        {
            trigger_error("Template : boucle $iterateName non trouvée",E_USER_NOTICE);
        }
    }
    
    // cherche des conditions et les résouds
    // le contexte sert à passer des variables de tests pour les alternatives
    function solveAlternative( $contents, $contexte=array() ){
        // on cherche des blocs conditionnels
        return preg_replace( '#(<!--%if:.*?-->.*?)<!--%endif%-->#sie', "\$this->replaceAlternative('$1',\$contexte)", $contents );
    }
    
    function replaceAlternative( $alternatives, $contexte )
    {
        // correction d'un "bug" php, plus d'info dans les commentaires
        // http://www.php.net/manual/en/function.preg-replace.php
        $alternatives = str_replace('\"', '"', $alternatives);
        // extrait le contenu des alternatives
        $alternativeContents = preg_split('#<!--%(if|else|elseif)(:.*?)?%-->#si', $alternatives, -1, PREG_SPLIT_NO_EMPTY );
        // extrait les conditions des alternative
        preg_match_all('#<!--%(if|elseif):(.*?)%-->#si', $alternatives, $alternativeConditions, PREG_SET_ORDER );
        $i=0;
        // boucle de test
        foreach( $alternativeContents as $alternativeContent )
        {
            // l'alternative existe ?
            if( isset( $alternativeConditions[$i] ) )
            {
                // $ -> $contexte
                $condition = preg_replace( '#\$#si', '$contexte', $alternativeConditions[$i][2] );
                // on éalue la condition
                eval('$condition='.$condition.';' );
                // si elle est vraie, on renvoie le contenu associé
                if( $condition )
                {
                    return $alternativeContent;
                }
                // autrement on continue la recherche d'une expression vraie
            }
            // pas d'alternative, c'est donc un else
            else
            {
                return $alternativeContent;
            }
            // alternative suivante
            $i++;
        }
    }
    
    function recursiveReplace( $tagName, $contentsArray )
    {
        preg_match("#<!--%recursion:$tagName%-->(.*?)<!--%endRecursion:$tagName%-->#si", $this->model, $matches );
        // on extrait le contenu de la recursivité
        $recursiveContent = $matches[1];
        $this->model = preg_replace("#<!--%recursion:$tagName%-->.*?<!--%endRecursion:$tagName%-->#si", $this->recursiveLoop($recursiveContent,$contentsArray), $this->model );
    }
    
    function recursiveLoop($recursiveContent,$contentsArray)
    {
        preg_match("#<!--%loop%-->(.*?)<!--%endLoop%-->#si", $recursiveContent, $matches );
        $iterateContent = $matches[1];
        $result='';
        if(count($contentsArray) > 0 )
        {
            foreach( $contentsArray as $contents )
            {
                $iterateContentBuffer = $this->solveAlternative( $iterateContent, $contents );
                foreach( $contents as $tag=>$content )
                {
                    if( is_array($content) )
                    {
                        $iterateContentBuffer = preg_replace("#<!--%recursion%-->#si", 
                            $this->recursiveLoop($recursiveContent,$content),
                            $iterateContentBuffer );
                    }
                    else
                    {
                        //$iterateContentBuffer = preg_replace("#<!--%recursion%-->#si", '', $iterateContentBuffer );
                        $iterateContentBuffer = preg_replace("#<!--%$tag%-->#si", $content, $iterateContentBuffer );
                    }
                }
                $result.=$iterateContentBuffer;
            }
        }    
        
        return preg_replace("#<!--%loop%-->.*?<!--%endLoop%-->#si", $result, $recursiveContent );
    }
    
    function setContext( $contextArray )
    {
        $this->contextArray=array_merge($this->contextArray,$contextArray);
    }
    
    // le paramètre final supprime toutes les balises de templates
    function toString( $final=true )
    {
        if( $final )
        {
            // résolutions des conditions
            $buffer = $this->solveAlternative( $this->model, $this->contextArray );
            // suppression des conditions
            $buffer = preg_replace("#<!--%if:.*?%-->.*?<!--%endif%-->#si", '', $buffer );
            // suppression des boucles
            $buffer = preg_replace("#<!--%loop:.*?%-->.*?<!--%endloop:.*?%-->#si", '', $buffer );
            // suppresion de tout les tags
            return preg_replace("#<!--%.*?%-->#si", '', $buffer );
        }
        else
        {
            return $this->model;
        }
    }
    
}

?>