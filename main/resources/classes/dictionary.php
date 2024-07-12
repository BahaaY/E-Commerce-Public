<?php
     
    class Dictionary {

        protected $link;

        public function __construct($link) {
            $this->link = $link;
        }

        public function get_lang($lang,$key) { 
            if($lang == "en" || $lang == "fr" || $lang == "ar"){
                $query_select="
                    SELECT 
                        $lang
                    FROM 
                        ".TableDictionary::$TABLE_NAME." 
                    WHERE 
                        ".TableDictionary::$COLUMN_DICTIONARY_ID." = ?
                    LIMIT 1
                ";
                $run_query_select=$this->link->prepare($query_select);
                $run_query_select->bindParam(1,$key); 
                if($run_query_select->execute()){
                    return $run_query_select->fetchColumn();
                }else{
                    return "NULL";
                }
            }else{
                return "NULL";
            }
            
        }

        public function get_dir($lang) {
            if($lang=='en' || $lang=='fr') {
                echo "style='direction:ltr !important;text-align:left !important'";
            }else if($lang=='ar'){
                echo "style='direction:rtl !important;text-align:right !important'";
            }else{
                echo "style='direction:ltr !important;text-align:left !important'";
            }
        }

    }
?>