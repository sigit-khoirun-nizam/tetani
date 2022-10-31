<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Petani_model extends CI_Model
{
    private $table = 'blw_petani';

    //validasi form, method ini akan mengembailkan data berupa rules validasi form       
    public function rules()
    {
        return [
            [
                'field' => 'kode_petani',  //samakan dengan atribute name pada tags input
                'label' => 'kode_petani',  // label yang kan ditampilkan pada pesan error
                'rules' => 'trim|required' //rules validasi
            ],

            [
                'field' => 'nama_petani',
                'label' => 'nama_petani',
                'rules' => 'trim|required'
            ],

            [
                'field' => 'no_hp',
                'label' => 'no_hp',
                'rules' => 'trim|required'
            ],

            [
                'field' => 'email',
                'label' => 'email',
                'rules' => 'trim|required'
            ],

            [
                'field' => 'alamat',
                'label' => 'alamat',
                'rules' => 'trim|required'
            ],

            [
                'field' => 'kota',
                'label' => 'kota',
                'rules' => 'trim|required'
            ],

            [
                'field' => 'provinsi',
                'label' => 'provinsi',
                'rules' => 'trim|required'
            ],

            [
                'field' => 'kategori_produk',
                'label' => 'kategori_produk',
                'rules' => 'trim|required'
            ],

            [
                'field' => 'status',
                'label' => 'status',
                'rules' => 'trim|required'
            ],

            [
                'field' => 'nama_bank',
                'label' => 'nama_bank',
                'rules' => 'trim|required'
            ],

            [
                'field' => 'rekening_bank',
                'label' => 'rekening_bank',
                'rules' => 'trim|required'
            ]
        ];
    }

    //menampilkan data petani berdasarkan id petani
    public function getById($id)
    {
        return $this->db->get_where($this->table, ["id_petani" => $id])->row();
        //query diatas seperti halnya query pada mysql 
        //select * from petani where id_petani='$id'
    }

    //menampilkan semua data petani
    public function getAll()
    {
        $this->db->from($this->table);
        $this->db->order_by("id_petani", "desc");
        $query = $this->db->get();
        return $query->result();
        //fungsi diatas seperti halnya query 
        //select * from petani order by id_petani desc
    }

    //menyimpan data petani
    public function save()
    {
        $data = array(
            "kode_petani" => $this->input->post('kode_petani'),
            "nama_petani" => $this->input->post('nama_petani'),
            "no_hp" => $this->input->post('no_hp'),
            "email" => $this->input->post('email'),
            "alamat" => $this->input->post('alamat'),
            "kota" => $this->input->post('kota'),
            "provinsi" => $this->input->post('provinsi'),
            "kategori_produk" => $this->input->post('kategori_produk'),
            "status" => $this->input->post('status'),
            "nama_bank" => $this->input->post('nama_bank'),
            "rekening_bank" => $this->input->post('rekening_bank')
        );
        return $this->db->insert($this->table, $data);
    }

    //edit data petani
    public function update()
    {
        $data = array(
            "kode_petani" => $this->input->post('kode_petani'),
            "nama_petani" => $this->input->post('nama_petani'),
            "no_hp" => $this->input->post('no_hp'),
            "email" => $this->input->post('email'),
            "alamat" => $this->input->post('alamat'),
            "kota" => $this->input->post('kota'),
            "provinsi" => $this->input->post('provinsi'),
            "kategori_produk" => $this->input->post('kategori_produk'),
            "status" => $this->input->post('status'),
            "nama_bank" => $this->input->post('nama_bank'),
            "rekening_bank" => $this->input->post('rekening_bank')
        );
        return $this->db->update($this->table, $data, array('id_petani' => $this->input->post('id_petani')));
    }

    //hapus data petani
    public function delete($id)
    {
        return $this->db->delete($this->table, array("id_petani" => $id));
    }
}

