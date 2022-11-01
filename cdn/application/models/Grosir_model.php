<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Grosir_model extends CI_Model
{
    private $table = 'blw_grosir';

    //validasi form, method ini akan mengembailkan data berupa rules validasi form       
    public function rules()
    {
        return [
            [
                'field' => 'kode_grosir',  //samakan dengan atribute name pada tags input
                'label' => 'kode_grosir',  // label yang kan ditampilkan pada pesan error
                'rules' => 'trim|required' //rules validasi
            ],

            [
                'field' => 'nama_grosir',
                'label' => 'nama_grosir',
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

    //menampilkan data grosir berdasarkan id grosir
    public function getById($id)
    {
        return $this->db->get_where($this->table, ["id_grosir" => $id])->row();
        //query diatas seperti halnya query pada mysql 
        //select * from grosir where id_grosir='$id'
    }

    //menampilkan semua data grosir
    public function getAll()
    {
        $this->db->from($this->table);
        $this->db->order_by("id_grosir", "desc");
        $query = $this->db->get();
        return $query->result();
        //fungsi diatas seperti halnya query 
        //select * from grosir order by id_grosir desc
    }

    //menyimpan data grosir
    public function save()
    {
        $data = array(
            "kode_grosir" => $this->input->post('kode_grosir'),
            "nama_grosir" => $this->input->post('nama_grosir'),
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

    //edit data grosir
    public function update()
    {
        $data = array(
            "kode_grosir" => $this->input->post('kode_grosir'),
            "nama_grosir" => $this->input->post('nama_grosir'),
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
        return $this->db->update($this->table, $data, array('id_grosir' => $this->input->post('id_grosir')));
    }

    //hapus data grosir
    public function delete($id)
    {
        return $this->db->delete($this->table, array("id_grosir" => $id));
    }
}

