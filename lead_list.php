
<!-- Main Content -->
<section class="content ecommerce-page">
    <div class="block-header">
        <div class="row">
            <div class="col-lg-7 col-md-6 col-sm-12">
                <h2>Current Leads
                <small class="text-muted">Welcome to UrbanWood Leads</small>
                </h2>
            </div>
           
        </div>
    </div>
    <div class="container-fluid">
       
        
       
        <div class="row clearfix">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="header">
                        <h2><strong>Recent</strong> Leads</h2>
                        <ul class="header-dropdown">
                            <li class="dropdown"> <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <i class="zmdi zmdi-more"></i> </a>
                                <ul class="dropdown-menu dropdown-menu-right slideUp">
                                    <li><a href="javascript:void(0);">Action</a></li>
                                    <li><a href="javascript:void(0);">Another action</a></li>
                                    <li><a href="javascript:void(0);">Something else</a></li>
                                </ul>
                            </li>
                            <li class="remove">
                                <a role="button" class="boxs-close"><i class="zmdi zmdi-close"></i></a>
                            </li>
                        </ul>
                    </div>
                    <div class="body table-responsive members_profiles">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th style="width:60px;">#ID</th>
                                    <th>Customer</th>
                                    <th>Email/Mobile</th>
                                    <th>City</th>
                                    <th>Indate</th>                           
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($leads)){
                                    foreach ($leads as $key => $lead) { ?>
                                <tr>
                                    <td>#<?=$lead->id;?></td>
                                    <td><?=$lead->name;?> </td>
                                    <td><?=$lead->email;?><br><?=$lead->mobile;?></td>
                                    <td><?=$lead->city;?><br><?=$lead->pincode;?></td>
                                    <td><?=date('d M Y H:i A',strtotime($lead->indate));?></td>
                                    <td>
                                        <?php if($lead->status<1){ ?>
                                        <span class="badge badge-warning">PENDING</span>
                                        <?php }else{ ?>
                                        <span class="badge badge-success">PROCESSING</span>
                                    <?php } ?>                                       
                                    </td>
                                    <td><a href="<?=base_url('leads/view/'.$lead->id);?>">[View]</a></td>
                                </tr>
                                    <?php } } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</section>