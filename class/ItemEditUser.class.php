<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class ItemEditUser extends ItemEdit {
    
    function loadDbdata() {
        $loaded = parent::loadDbdata();
        if (!$loaded) return $loaded;
        
        $SQL = 'SELECT g.*, z.bezeichnung zustaendigkeit FROM mm_gebaeude_user gu ' . "\n"
              .' LEFT JOIN mm_gebaeude_zustaendigkeitsarten z ' ."\n"
              .' ON(gu.zustaendigkeits_id = z.zustaendigkeits_id)' ."\n"
              .' LEFT JOIN mm_stamm_gebaeude g ' ."\n"
              .' ON(g.id = g.gebaeude_id)' ."\n"
              .' WHERE gu.' . (int)$this->arrConf["PrimaryKey"] . ' = ' . (int)$this->id . "\n"
              .' ORDER BY z.zustaendigkeits_id, g.stadtname, g.adresse';
        $r = $this->db_query($SQL);
        
        if ($r) {
            $n = MyDB::num_rows($r);
            for($i = 0; $i < $n; ++$i) {
                $row = MyDB::fetch_assoc($r);
                $this->arrDbdata[$row['zustaendigkeit']]['stadtname'][] = $row;
            }
            $this->db_free_result($r);
            return true;
        } else {
            throw new Exception('#'.__LINE__ . ' ' . __FILE__ . ' ' . MyDB::error() . "\n" . $SQL);
        }
        return $loaded;
    }
    
    function saveInput() {
        parent::saveInput();
    }
    
    function saveZustaendigkeiten($data) {
        // data(gebaeude_id=>int, zustaendigkeits_id=>int)
        
        $SQL = 'DELETE FROM mm_gebaeude_user WHERE uid = ' . (int)$this->id;
        
        for($i = 0; $i < count($data); ++$i) {
            
        }
    }
}
