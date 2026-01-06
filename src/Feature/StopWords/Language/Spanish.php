<?php

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Feature\StopWords\Language;

use CoralMedia\PhpIr\Feature\StopWords\StopWordsSet;

final class Spanish
{
    public static function default(): StopWordsSet
    {
        return new StopWordsSet([
            'a','acerca','actualmente','adelante','ademas','adonde','aestarian',
            'afirmo','agrego','ahi','ahora','al','algo','algun','alguna','algunas',
            'alguno','algunos','allende','alrededor','ambos','amen','ampliamos',
            'ante','anterior','antes','anadio','apenas','aproximadamente','aquel',
            'aquellas','aquellos','aqui','arriba','aseguro','asi','atras','aun',
            'aunque','ayer','bajo','bastante','bien','buen','buena','buenas',
            'bueno','buenos','cabe','cabo','cada','casi','cerca','cierta','ciertas',
            'cierto','ciertos','cinco','circa','comento','como','con','conmigo',
            'conozco','conocer','conseguimos','conseguir','considera','considero',
            'consigo','consigue','consiguen','consigues','contigo','contra','cosas',
            'creo','cual','cuales','cualquier','cuando','cuanto','cuatro','cuenta',
            'da','dado','dan','dar','de','debe','deben','debido','decir','dejo',
            'del','delas','demas','dentro','desde','despues','dice','dicen','dicho',
            'dieron','diferente','diferentes','dijeron','dijo','dio','donde','dos',
            'durante','e','ejemplo','el','ella','ellas','ello','ellos','embargo',
            'emplean','emplear','empleas','empleo','en','encima','encuentra',
            'entonces','entre','era','erais','eramos','eran','erar','eras','eres',
            'es','esa','esas','ese','eso','esos','esta','estaba','estabais',
            'estabamos','estaban','estabas','estad','estada','estadas','estado',
            'estados','estais','estamos','estan','estando','estar','estara',
            'estaran','estaras','estare','estareis','estaremos','estaria',
            'estariais','estariamos','estarian','estarias','estas','este','esteis',
            'estemos','esten','estes','esto','estos','estoy','estuve','estuviera',
            'estuvierais','estuvierramos','estuvieran','estuvieras','estuvieron',
            'estuviese','estuvieseis','estuviemos','estuviesen','estuvieses',
            'estuvimos','estuviste','estuvisteis','estuvo','ex','excepto','existe',
            'existen','explico','expreso','fin','fue','fuera','fuerais','fueramos',
            'fueran','fueras','fueron','fuerza','fuese','fueseis','fuesemos',
            'fuesen','fueses','fui','fuimos','fuiste','fuisteis','gran','grandes',
            'ha','habeis','haber','habia','habiais','habiamos','habian','habias',
            'habida','habidas','habido','habidos','habiendo','habra','habran',
            'habras','habre','habreis','habremos','habria','habriais','habriamos',
            'habrian','habrias','hace','hacemos','hacen','hacer','hacerlo','haces',
            'hacia','haciendo','hago','han','has','hasta','hay','haya','hayais',
            'hayamos','hayan','hayas','haz','he','hecho','hemo','hemos','hicieron',
            'hizo','hoy','hube','hubiera','hubierais','hubieramos','hubieran',
            'hubieras','hubieron','hubiese','hubieseis','hubiesemos','hubiesen',
            'hubieses','hubimos','hubiste','hubisteis','hubo','igual','incluso',
            'indico','informo','intenta','intentamos','intentan','intentar',
            'intentas','intento','ir','junto','la','lado','largo','las','le','les',
            'llego','lleva','llevar','lo','los','luego','lugar','manera',
            'manifesto','mas','mayor','me','mediante','mejor','menciono','menos',
            'mi','mia','mias','mientras','mio','mios','mis','misma','mismas',
            'mismo','mismos','modo','momento','mucha','muchas','mucho','muchos',
            'muy','na','nada','nadie','ni','ningun','ninguna','ningunas','ninguno',
            'ningunos','no','nos','nosotras','nosotros','nuestra','nuestras',
            'nuestro','nuestros','nueva','nuevas','nuevo','nuevos','nunca','o',
            'ocho','os','otra','otras','otro','otros','pa','par','para','parece',
            'parte','partir','pasada','pasado','pero','pesar','poca','pocas','poco',
            'pocos','podemos','poder','podra','podran','podria','podriais',
            'podriamos','podrian','podrias','poner','por','porque','posible',
            'primer','primera','primero','primeros','principalmente','pro','propia',
            'propias','propio','propios','proximo','proximos','pudo','pueda',
            'puede','pueden','puedo','pues','que','quedo','queremos','quien',
            'quienes','quiere','realizado','realizar','realizo','respecto','sabe',
            'sabeis','sabemos','saben','saber','sabes','salvo','se','sea','seais',
            'seamos','sean','seas','segun','segunda','segundo','seis','sentid',
            'sentida','sentidas','sentido','sentidos','sentir','senalo','ser',
            'sera','seran','seras','sere','sereis','seremos','seria','seriais',
            'seriamos','serian','serias','si','sido','siempre','siendo','siente',
            'siete','sigue','siguiente','sin','sino','sintiendo','so','sobre',
            'sois','sola','solamente','solas','solo','solos','somos','son','soy',
            'su','sus','suya','suyas','suyo','suyos','tal','tambien','tampoco',
            'tan','tanto','te','tendra','tendran','tendras','tendre','tendreis',
            'tendremos','tendria','tendriais','tendriamos','tendrian','tendrias',
            'tened','teneis','tenemos','tener','tenga','tengais','tengamos',
            'tengan','tengas','tengo','tenia','teniais','teniamos','tenian',
            'tenias','tenida','tenidas','tenido','tenidos','teniendo','tercera',
            'ti','tiempo','tiene','tienen','tienes','toda','todas','todavia','todo',
            'todos','total','trabaja','trabajamos','trabajan','trabajar',
            'trabajas','trabajo','tras','trata','traves','tres','tu','tus','tuve',
            'tuviera','tuvierais','tuvieramos','tuvieran','tuvieras','tuvieron',
            'tuviese','tuvieseis','tuviesen','tuvieses','tuvimos',
            'tuviste','tuvisteis','tuvo','tuya','tuyas','tuyo','tuyos','ultima',
            'ultimar','ultimas','ultimo','ultimos','un','una','unas','uno','unos',
            'usa','usamos','usan','usar','usas','uso','usted','va','vais','valor',
            'vamos','van','varias','varios','vaya','veces','ver','verdad',
            'verdadera','verdadero','versus','vez','via','vosotras','vosotros',
            'voy','vuestra','vuestras','vuestro','vuestros','y','ya','yo',
        ]);
    }
}
