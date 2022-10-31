<div class="container pt-5">
    <h3><?= $title ?></h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb ">
            <li class="breadcrumb-item"><a>Data Transporter</a></li>
            <li class="breadcrumb-item "><a href="<?= base_url('datatransporter/index'); ?>">List Data Transporter</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah Data</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <?php
                    //create form
                    $attributes = array('id' => 'FrmAddMahasiswa', 'method' => "post", "autocomplete" => "off");
                    echo form_open('', $attributes);
                    ?>

                    <div class="form-group row">
                        <label for="nama_driver" class="col-sm-4 col-form-label">Nama Driver</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nama_driver" name="nama_driver" value=" <?= set_value('nama'); ?>">
                            <small class="text-danger">
                                <?php echo form_error('nama_driver') ?>
                            </small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="nama_transporter" class="col-sm-4 col-form-label">Nama Transporter</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nama_transporter" name="nama_transporter" value=" <?= set_value('nama_transporter'); ?>">
                            <small class="text-danger">
                                <?php echo form_error('nama_transporter') ?>
                            </small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="no_hp" class="col-sm-4 col-form-label">No HP</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="no_hp" name="no_hp" value=" <?= set_value('no_hp'); ?>">
                            <small class="text-danger">
                                <?php echo form_error('no_hp') ?>
                            </small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="status" class="col-sm-4 col-form-label">Status</label>
                        <div class="col-sm-10">
                            <select class="form-control" id="status" name="status">
                                <option value="Pilih" selected disabled>Pilih</option>
                                <option value="Aktif" <?php if (set_value('status') == "Aktif") : echo "selected";
                                                        endif; ?>>Aktif</option>
                                <option value="Tidak Aktif" <?php if (set_value('status') == "Tidak Aktif") : echo "selected";
                                                            endif; ?>>Tidak Aktif</option>
                            </select>
                            <small class="text-danger">
                                <?php echo form_error('status') ?>
                            </small>
                        </div>
                    </div>

                    <!--form-->
                    <div class="form-group row mt-2">
                        <div class="col-sm-10">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a class="btn btn-secondary" href="javascript:history.back()">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>