<div class="box">
    <div class="box-header">
        <h3 class="box-title">Restaurants</h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <div class="row" style="margin-bottom: 10px"> 
            <form id='formAddCity' name='formAddCity' method='post' action='<?php echo base_url() . ADMIN_ACTION_ADDCITY; ?>' enctype="multipart/form-data">			
                <div class="col-md-3" >
                    <input class='form-control' name='cityName' id='cityName' placeholder='New City'/></a>
                </div>      
                <div class="col-md-3" >
                    <select class='form-control' id='cityCountry' name='cityCountry'>
                        <?php
                        foreach ($countrys as $country) {
                            echo "<option value='" . $country->no . "'>" . $country->name . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-3" >
                    <select class='form-control' id='cityCurrency' name='cityCurrency'>
                        <?php
                        foreach ($currencys as $currency) {
                            echo "<option value='" . $currency->no . "'>" . $currency->name . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <input type="file" accept="image/*" name="uploadImage" id="uploadImage" style="visibility: hidden; width: 1px; height: 1px" multiple onchange="previewFile()">                
                <div class="col-md-3" >
                    <button type="button" class ="btn btn-block btn-success" onclick='onAddCity()'>Add City with Image</button>
                </div>
            </form>
        </div>
        <div class="box-body">
            <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Sub Category</th>
                        <th>Address</th>                   
                        <th>Opening</th>                    
                        <th>Reservations</th>    
                        <th>Promote</th>                    
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 0;
                    foreach ($restaurants as $restaurant) {
                        $i++;
                        ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo $restaurant->name; ?></td>
                            <td><?php echo $restaurant->cname; ?></td>
                            <td><?php echo $restaurant->sname; ?></td>
                            <td><?php echo $restaurant->address; ?></td>
                            <td><?php echo $restaurant->start_time . "~" . $restaurant->end_time; ?></td>                        
                            <td><?php echo $restaurant->countReserve; ?></td>
                            <td>
                                <?php
                                if ($restaurant->feature == 0)
                                    echo "None";
                                else
                                    echo "Featured";
                                ?>
                            </td>                        
                            <td>
                                <?php
                                if ($restaurant->feature == 0) {
                                    echo "<a href='" . base_url() . ADMIN_ACTION_RESTAURANT_FEATURE . "/" . $restaurant->no . "'>Set Feature</a>&nbsp;&nbsp;&nbsp;";
                                } else {
                                    echo "<a href='" . base_url() . ADMIN_ACTION_RESTAURANT_FEATURE . "/" . $restaurant->no . "'>Set Non-Feature</a>&nbsp;&nbsp;&nbsp;";
                                }
                                ?>
                                <a href='<?php echo base_url() . ADMIN_ACTION_RESTAURANT_DELETE . "/" . $restaurant->no; ?>'>Delete</a>&nbsp;&nbsp;&nbsp;
                            </td>
                        </tr>
    <?php
}
?>
                </tbody>            
            </table>
        </div>
        <!-- /.box-body -->
    </div>