<div class="container">
    <h3 class="mb-2"><?= $title ?></h3>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <?php
                    //create form
                    $attributes = array('id' => 'FrmAddMahasiswa', 'method' => "post", "autocomplete" => "off");
                    echo form_open('', $attributes);
                    ?>

                    <div class="form-group row">
                        <label for="kode_transporter" class="col-sm-4 col-form-label">Kode</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="kode_transporter" name="kode_transporter" value=" <?= $kode; ?>" readonly>
                            <small class="text-danger">
                                <?php echo form_error('kode_transporter') ?>
                            </small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="nama" class="col-sm-4 col-form-label">Nama</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nama" name="nama" value=" <?= set_value('nama'); ?>">
                            <small class="text-danger">
                                <?php echo form_error('nama') ?>
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
                        <label for="email" class="col-sm-4 col-form-label">Email</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="email" name="email" value=" <?= set_value('email'); ?>">
                            <small class="text-danger">
                                <?php echo form_error('email') ?>
                            </small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="alamat" class="col-sm-4 col-form-label">Alamat</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="alamat" name="alamat" value=" <?= set_value('alamat'); ?>">
                            <small class="text-danger">
                                <?php echo form_error('alamat') ?>
                            </small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="provinsi" class="col-sm-4 col-form-label">Provinsi</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="provinsi" name="provinsi" value=" <?= set_value('provinsi'); ?>">
                            <small class="text-danger">
                                <?php echo form_error('provinsi') ?>
                            </small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="kota" class="col-sm-4 col-form-label">Kota</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="kota" name="kota" value=" <?= set_value('kota'); ?>">
                            <small class="text-danger">
                                <?php echo form_error('kota') ?>
                            </small>
                        </div>
                    </div>
                    <!--form-->
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <?php
                    //create form
                    $attributes = array('id' => 'FrmAddMahasiswa', 'method' => "post", "autocomplete" => "off");
                    echo form_open('', $attributes);
                    ?>

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

                    <div class="form-group row">
                        <label for="account_bank" class="col-sm-4 col-form-label">Account Bank</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="account_bank" name="account_bank" value=" <?= set_value('account_bank'); ?>">
                            <small class="text-danger">
                                <?php echo form_error('account_bank') ?>
                            </small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="rekening_bank" class="col-sm-4 col-form-label">Rekening Bank</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="rekening_bank" name="rekening_bank" value=" <?= set_value('rekening_bank'); ?>">
                            <small class="text-danger">
                                <?php echo form_error('rekening_bank') ?>
                            </small>
                        </div>
                    </div>

                    <div class="form-group row mt-2">
                        <div class="col-sm-10">
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
