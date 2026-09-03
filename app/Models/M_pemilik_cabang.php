<?php

namespace App\Models;

use CodeIgniter\Model;

class M_pemilik_cabang extends Model
{
    protected $table = 'tbl_data_cabang';
    protected $primaryKey = 'id_cabang';
    protected $useAutoIncrement = true;
    protected $allowedFields = ['id_website', 'nama_cabang', 'latitude_cabang', 'longitude_cabang', 'alamat_cabang', 'wa_cabang'];

    public function getCabangByWebsite($id_website)
    {
        return $this->where('id_website', $id_website)->findAll();
    }

    public function deleteByWebsite($id_website)
    {
        return $this->where('id_website', $id_website)->delete();
    }
}
