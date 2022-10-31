<div class="container">
    <h3><?= $title ?></h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb ">
            <li class="breadcrumb-item"><a>Data Transporter</a></li>
            <li class="breadcrumb-item "><a href="<?= base_url('datatransporter/index'); ?>">List Data Transporter</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Data</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <?php
                    //create form
                    $attributes = array('id' => 'FrmEditMahasiswa', 'method' => "post", "autocomplete" => "off");
                    echo form_open('', $attributes);
                    ?>
                    <div class="form-group row">
                        <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                        <div class="col-sm-10">
                            <input type="hidden" class="form-control" id="id" name="id" value=" <?= $data_transporter->id; ?>">
                            <input type="text" class="form-control" id="nama" name="nama" value=" <?= $data_transporter->nama; ?>">
                            <small class="text-danger">
                                <?php echo form_error('nama') ?>
                            </small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="no hp" class="col-sm-2 col-form-label">No Hp</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="no_hp" name="no_hp" value=" <?= $data_transporter->no_hp; ?>">
                            <small class="text-danger">
                                <?php echo form_error('no_hp') ?>
                            </small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="email" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="email" name="email" value=" <?= $data_transporter->email; ?>">
                            <small class="text-danger">
                                <?php echo form_error('email') ?>
                            </small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="alamat" class="col-sm-2 col-form-label">Alamat</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" id="alamat" name="alamat" rows="3"><?= $data_transporter->alamat; ?></textarea>
                            <small class="text-danger">
                                <?php echo form_error('alamat') ?>
                            </small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="provinsi" class="col-sm-2 col-form-label">Provinsi</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="provinsi" name="provinsi" value=" <?= $data_transporter->provinsi; ?>">
                            <small class="text-danger">
                                <?php echo form_error('provinsi') ?>
                            </small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="kota" class="col-sm-2 col-form-label">Kota</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="kota" name="kota" value=" <?= $data_transporter->kota; ?>">
                            <small class="text-danger">
                                <?php echo form_error('kota') ?>
                            </small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="status" class="col-sm-2 col-form-label">Status</label>
                        <div class="col-sm-10">
                            <select class="form-control" id="status" name="status">
                                <option value="Pilih" selected disabled>Pilih</option>
                                <option value="Aktif" <?php if ($data_transporter->status == "Aktif") : echo "selected";
                                                        endif; ?>>Aktif</option>
                                <option value="Tidak Aktif" <?php if ($data_transporter->status == "Tidak Aktif") : echo "selected";
                                                            endif; ?>>Tidak Aktif</option>
                            </select>
                            <small class="text-danger">
                                <?php echo form_error('Agama') ?>
                            </small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="account bank" class="col-sm-2 col-form-label">Account Bank</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="account_bank" name="account_bank" value=" <?= $data_transporter->account_bank; ?>">
                            <small class="text-danger">
                                <?php echo form_error('account_bank') ?>
                            </small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="rekening bank" class="col-sm-2 col-form-label">Rekening Bank</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="rekening_bank" name="rekening_bank" value=" <?= $data_transporter->rekening_bank; ?>">
                            <small class="text-danger">
                                <?php echo form_error('rekening_bank') ?>
                            </small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-10 offset-md-9">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a class="btn btn-secondary" href="javascript:history.back()">Kembali</a>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
