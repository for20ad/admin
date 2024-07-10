<?php
namespace App\Models;

use CodeIgniter\Model;

class ChatModel extends Model
{

    public function addMessage( $param = [] )
    {
        $aReturn                                    = false;

        $builder                                    = $this->db->table( 'WJ_CHAT_MESSAGES' );
        foreach( $param as $key=> $val )
        {
            $builder->set( $key, $val);
        }

        $aReturn                                    = $builder->insert();
        return $aReturn;
    }

    public function getMessages( $param = [] )
    {
        $aReturn                                    = [];
        $builder                                    = $this->db->table( 'WJ_CHAT_MESSAGES' );
        $builder->where( 'ROOM_ID', _elm( $param, 'ROOM_ID' ) );
        $builder->where( 'TIMESTAMP >=',  _elm( $param, 'SINCE' ) );
        $builder->orderBy( 'TIMESTAMP',  'DESC' );


        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getResultArray();
        }
        return $aReturn;
    }

    public function addRoom( $param = [] )
    {
        $aReturn                                    = [];
        $builder                                    = $this->db->table( 'WJ_CHAT_ROOMINFO' );

        foreach( $param as $key=> $val )
        {
            $builder->set( $key, $val);
        }

        $aReturn                                    = $builder->insert();
        return $aReturn;

    }

    public function getMyRoomLists( $param = [] )
    {
        $aReturn                                    = [];

        $builder                                    = $this->db->table( 'WJ_CHAT_ROOMINFO R' );
        $builder->select( 'R.*' );
        $builder->select( 'M.MESSAGE LAST_MESSAGE, M.TIMESTAMP LAST_TIMESTAMP' );
        $builder->join("(
            SELECT wm1.MESSAGE, wm1.TIMESTAMP, wm1.ROOM_ID
            FROM WJ_CHAT_MESSAGES wm1
            INNER JOIN (
                SELECT ROOM_ID, MAX(TIMESTAMP) AS MAX_TIMESTAMP
                FROM WJ_CHAT_MESSAGES
                GROUP BY ROOM_ID
            ) wm2 ON wm1.ROOM_ID = wm2.ROOM_ID AND wm1.TIMESTAMP = wm2.MAX_TIMESTAMP
        ) M", 'R.ROOM_ID = M.ROOM_ID', 'LEFT');
        // $builder->join( "( SELECT MESSAGE, TIMESTAMP, USER_ID FROM WJ_CHAT_MESSAGES WHERE ROOM_ID = R.ROOM_ID ORDER BY TIMESTAMP DESC LIMIT 1 ) M",  '1=1', 'LEFT' );
        $builder->where( 'JSON_UNQUOTE(JSON_EXTRACT( R.ROOM_INFO, \'$.creater\'))', _elm( $param, 'USER_ID') );
        $builder->orWhere( 'JSON_UNQUOTE(JSON_EXTRACT( R.ROOM_INFO, \'$.join_member\'))', _elm( $param, 'USER_ID') );
        $builder->orderBy( 'M.TIMESTAMP',  'DESC' );

        $query                                      = $builder->get();


        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getResultArray();
        }
        return $aReturn;
    }

    public function getRoomInfo( $param = [] )
    {

        $aReturn                                    = [];
        $builder                                    = $this->db->table( 'WJ_CHAT_ROOMINFO' );

        $builder->where( 'IS_ACTIVE', 'true' );
        $builder->where( 'ROOM_ID', _elm( $param, 'ROOM_ID' ) );

        $query                                      = $builder->get();

        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
        }
        return $aReturn;
    }
}