<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kemitraan_model extends CI_Model
{
    private $table = 'blw_kemitraan';

    //validasi form, method ini akan mengembailkan data berupa rules validasi form       
    public function rules()
    {
        return [
            [
                'field' => 'kode_kemitraan',  //samakan dengan atribute name pada tags input
                'label' => 'kode_kemitraan',  // label yang kan ditampilkan pada pesan error
                'rules' => 'trim|required' //rules validasi
            ],

            [
                'field' => 'nama_kemitraan',
                'label' => 'nama_kemitraan',
                'rules' => 'trim|required'
            ],

            [
                'field' => 'kategori_produk',
                'label' => 'kategori_produk',
                'rules' => 'trim|required'
            ],

            [
                'field' => 'no_hp',
                'label' => 'no_hp',
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
                'field' => 'nama_rekening',
                'label' => 'nama_rekening',
                'rules' => 'trim|required'
            ],

            [
                'field' => 'no_rekening',
                'label' => 'no_rekening',
                'rules' => 'trim|required'
            ],

            [
                'field' => 'status',
                'label' => 'status',
                'rules' => 'trim|required'
            ]
        ];
    }

    //menampilkan data kemitraan berdasarkan id kemitraan
    public function getById($id)
    {
        return $this->db->get_where($this->table, ["id_kemitraan" => $id])->row();
        //query diatas seperti halnya query pada mysql 
        //select * from kemitraan where id_kemitraan='$id'
    }

    //menampilkan semua data kemitraan
    public function getAll()
    {
        $this->db->from($this->table);
        $this->db->order_by("id_kemitraan", "desc");
        $query = $this->db->get();
        return $query->result();
        //fungsi diatas seperti halnya query 
        //select * from kemitraan order by id_kemitraan desc
    }

    //menyimpan data kemitraan
    public function save()
    {
        $data = array(
            "kode_kemitraan" => $this->input->post('kode_kemitraan'),
            "nama_kemitraan" => $this->input->post('nama_kemitraan'),
            "kategori_produk" => $this->input->post('kategori_produk'),
            "no_hp" => $this->input->post('no_hp'),
            "kota" => $this->input->post('kota'),
            "provinsi" => $this->input->post('provinsi'),
            "nama_rekening" => $this->input->post('nama_rekening'),
            "no_rekening" => $this->input->post('no_rekening'),
            "status" => $this->input->post('status')

        );
        return $this->db->insert($this->table, $data);
    }

    //edit data kemitraan
    public function update()
    {
        $data = array(
            "kode_kemitraan" => $this->input->post('kode_kemitraan'),
            "nama_kemitraan" => $this->input->post('nama_kemitraan'),
            "kategori_produk" => $this->input->post('kategori_produk'),
            "no_hp" => $this->input->post('no_hp'),
            "kota" => $this->input->post('kota'),
            "provinsi" => $this->input->post('provinsi'),
            "nama_rekening" => $this->input->post('nama_rekening'),
            "no_rekening" => $this->input->post('no_rekening'),
            "status" => $this->input->post('status')
           
        );
        return $this->db->update($this->table, $data, array('id_kemitraan' => $this->input->post('id_kemitraan')));
    }

    //hapus data kemitraan
    public function delete($id)
    {
        return $this->db->delete($this->table, array("id_kemitraan" => $id));
    }
}

