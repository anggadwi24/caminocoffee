<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <h3 class="page-title"><?= $page ?></h3>
            <ul class="breadcrumb">
                <?= $breadcrumb ?>
               
            </ul>
        </div>
        <div class="col-auto float-right ml-auto">
            <?= $right ?>
           
        </div>
    </div>
</div>
<form id="formSearch">
<div class="row filter-row">
        <div class="col-sm-6 col-md-3">  
            <div class="form-group form-focus select-focus">
                <select class="select floating" name="pegawai"> 
                    <option disabled selected>-</option>
                   <?php 
                       foreach($record->result() as $row){
                        echo "<option value='".$row->username."'>".$row->name."</option>";
                       }
                    ?>
                </select>
                <label class="focus-label">Pilih Pegawai</label>
            </div>
        </div>
        <div class="col-sm-6 col-md-3"> 
            <div class="form-group form-focus select-focus">
                <select class="select floating" name="month"> 
                   <?php 
                        for($a=0;$a<12;$a++){
                            if($bulan[$a]['code'] == date('m')){
                                echo "<option value='".$bulan[$a]['code']."' selected>".$bulan[$a]['bulan']."</option>";

                            }else{
                                echo "<option value='".$bulan[$a]['code']."'>".$bulan[$a]['bulan']."</option>";

                            }
                        }
                    ?>
                </select>
                <label class="focus-label">Pilih Bulan</label>
            </div>
        </div>
        <div class="col-sm-6 col-md-3"> 
            <div class="form-group form-focus select-focus">
                <select class="select floating" name="year"> 
                    <option value="<?= date('Y',strtotime('-2 Year'))?>"><?= date('Y',strtotime('-2 Year'))?></option>
                    <option value="<?= date('Y',strtotime('-1 Year'))?>"><?= date('Y',strtotime('-1 Year'))?></option>
                    <option value="<?= date('Y')?>" selected><?= date('Y')?></option>
                    <option value="<?= date('Y',strtotime('+1 Year'))?>"><?= date('Y',strtotime('+1 Year'))?></option>
                    <option value="<?= date('Y',strtotime('+2 Year'))?>"><?= date('Y',strtotime('+2 Year'))?></option>
                </select>
                <label class="focus-label">Pilih Tahun</label>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">  
            <button class="btn btn-success btn-block"> Search </button>  
        </div>     
    </div>
</form>


<div class="row">
    <div class="col-lg-12" id="data">
        
    </div>
</div>