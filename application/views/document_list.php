<?php foreach($documentList->getDocumentsSorted() as $lDocument): ?>

<p>
    <?php
        echo $lDocument->toFormattedString();
        if(strlen($lDocument->getFileName()) > 0){
            if (isset($is_preview) && $is_preview) {
                // don't include links in preview mode!
                echo "&nbsp;(" . substr(strrchr($lDocument->getFileName(), "."), 1) . ", " . round($lDocument->getFileSize("MB"), 2) . " MB)";
            } else {
                echo "&nbsp;(<a target=\"_self\" href=\"../files/" . $documentList->getHashedId() . "/" . $lDocument->getHashedId() . "\">";
                echo substr(strrchr($lDocument->getFileName(), "."), 1) . ", " . round($lDocument->getFileSize("MB"), 2) . " MB";
                echo "</a>)";
            }
        }
        else{
            echo "&nbsp;(Kein Dokument hinterlegt)";
        }
    ?>
</p>

<?php endforeach ?>

