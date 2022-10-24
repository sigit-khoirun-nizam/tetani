<div class="container pt-5">
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
                        <label for="Nama" class="col-sm-2 col-form-label">Nama</label>
                        <div class="col-sm-10">
                            <input type="hidden" class="form-control" id="IdMhsw" name="IdMhsw" value=" <?= $data_mahasiswa->IdMhsw; ?>">
                            <input type="text" class="form-control" id="Nama" name="Nama" value=" <?= $data_mahasiswa->Nama; ?>">
                            <small class="text-danger">
                                <?php echo form_error('Nama') ?>
                            </small>
                        </div>
                    </div>

                    <fieldset class="form-group">
                        <div class="row">
                            <legend class="col-form-label col-sm-2 pt-0">Jenis Kelamin</legend>
                            <div class="col-sm-10">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="JenisKelamin" name="JenisKelamin" value="Laki-laki" <?php if ($data_mahasiswa->JenisKelamin == "Laki-laki") : echo "checked";
                                    endif; ?>>
                                    <label class="form-check-label" for="JenisKelamin">
                                        Laki-laki
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="JenisKelamin2" name="JenisKelamin" value="Perempuan" <?php if ($data_mahasiswa->JenisKelamin == "Perempuan") : echo "checked";
                                    endif; ?>>
                                    <label class="form-check-label" for="JenisKelamin2">
                                        Perempuan
                                    </label>
                                </div>
                                <small class="text-danger">
                                    <?php echo form_error('JenisKelamin') ?>
                                </small>
                            </div>
                        </div>
                    </fieldset>

                    <div class="form-group row">
                        <div class="col-sm-10 offset-md-2">
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