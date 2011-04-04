<?php print displayStatus();?>
<h2><?php echo $title;?></h2>
<p><?php echo anchor("customer/admin/create", "Create new customer");?>
<?php

if (count($customers)){
    echo "<table id='tablesorter' class='tablesorter' border='1' cellspacing='0' cellpadding='3' width='100%'>\n";
    echo "<thead>\n<tr valign='top'>\n";
    echo "<th>Customer ID</th>\n<th>First name</th><th>Last name</th><th>Phone Number</th><th>Email</th><th>Address</th><th>City</th><th>Actions</th>\n";
    echo "</tr>\n</thead>\n<tbody>\n";
    foreach ($customers as $key => $list){
        echo "<tr valign='top'>\n";
        echo "<td align='center'>".$list['customer_id']."</td>\n";
        echo "<td align='center'>".$list['customer_first_name']."</td>\n";
        echo "<td align='center'>".$list['customer_last_name']."</td>\n";
        echo "<td align='center'>".$list['phone_number']."</td>\n";
        echo "<td align='center'>".$list['email']."</td>\n";
        echo "<td align='center'>".$list['address']."</td>\n";
        echo "<td align='center'>".$list['city']."</td>\n";
        echo "<td align='center'>";
        echo anchor('customer/admin/edit/'.$list['customer_id'],'edit');
        echo " | ";
        // can't use kaimonokago/admin/delete, at the moment have to use customer/admin/delete
        // this must check orphans, kaimonokago/models/delete is used after checking it
        echo anchor('customer/admin/delete/'.$list['customer_id'],'delete',array("onclick"=>"return confirmSubmit('".$list['customer_first_name']."')"));
        echo "</td>\n";
        echo "</tr>\n";
    }
    echo "</tbody>\n</table>";
}
?>