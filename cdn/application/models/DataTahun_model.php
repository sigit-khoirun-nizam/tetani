<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DataTahun_model extends CI_Model
{
    private $table = 'blw_tahun';

    public function rules()
    {
        return [
            [
                'field' => 'nama_tahun',
                'label' => 'nama tahun',
                'rules' => 'trim|required'
            ],
            [
                'field' => 'status_tahun',
                'label' => 'status tahun',
                'rules' => 'trim|required'
            ],
        ];
    }

    public function getById($id)
    {
        return $this->db->get_where($this->table, ["id" => $id])->row();
    }

    public function getAll()
    {
        $this->db->from($this->table);
        $this->db->order_by('id', 'desc');
        $query = $this->db->get();
        return $query->result();
    }

    public function save()
    {
        $data = array(
            "nama_tahun" => $this->input->post('nama_tahun'),
            "status_tahun" => $this->input->post('status_tahun'),
        );

        return $this->db->insert($this->table, $data);
    }

    public function update()
    {
        $data = array(
            "nama_tahun" => $this->input->post('nama_tahun'),
            "status_tahun" => $this->input->post('status_tahun'),
        );

        return $this->db->update($this->table, $data, array('id' => $this->input->post('id')));
    }

    public function delete($id)
    {
        return $this->db->delete($this->table, array('id' => $id));
    }
}
