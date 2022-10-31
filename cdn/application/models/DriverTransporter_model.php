<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DriverTransporter_model extends CI_Model
{
    private $table = 'blw_driver_transporter';

    public function rules()
    {
        return [
            [
                'field' => 'kode_driver',
                'label' => 'kode_driver'
            ],
            [
                'field' => 'nama_driver',
                'label' => 'nama driver',
                'rules' => 'trim|required'
            ],
            [
                'field' => 'nama_transporter',
                'label' => 'nama transporter',
                'rules' => 'trim|required'
            ],
            [
                'field' => 'no_hp',
                'label' => 'no hp',
                'rules' => 'trim|required|numeric|min_length[9]|max_length[15]'
            ],
            [
                'field' => 'status',
                'label' => 'status',
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
            "kode_driver" => $this->input->post('kode_driver'),
            "nama_driver" => $this->input->post('nama_driver'),
            "nama_transporter" => $this->input->post('nama_transporter'),
            "no_hp" => $this->input->post('no_hp'),
            "status" => $this->input->post('status'),
        );

        return $this->db->insert($this->table, $data);
    }

    public function update()
    {
        $data = array(
            "kode_driver" => $this->input->post('kode_driver'),
            "nama_driver" => $this->input->post('nama_driver'),
            "nama_transporter" => $this->input->post('nama_transporter'),
            "no_hp" => $this->input->post('no_hp'),
            "status" => $this->input->post('status'),
        );

        return $this->db->update($this->table, $data, array('id' => $this->input->post('id')));
    }

    public function delete($id)
    {
        return $this->db->delete($this->table, array('id' => $id));
    }
}


