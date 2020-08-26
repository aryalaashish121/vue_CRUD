<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Simple Data Table</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
  <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<link rel="stylesheet" href="css/style.css" />
<link rel="stylesheet" href="css/modal.css" />

<!-- <script>
$(document).ready(function(){
	$('[data-toggle="tooltip"]').tooltip();
});
</script> -->
</head>
<body>
<div class="container-xl" id="crudApp">
    <div class="table-responsive">
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-8"><h2>Customer <b>Details</b></h2></div>
                    <div class="col-sm-4">
                        <div class="search-box">
                            <i class="material-icons">&#xE8B6;</i>
                            <input type="text" class="form-control" placeholder="Search&hellip;" v-model="queryValue" @keyup="searchData()"/>
                        </div>
                        <div class="col-md-6" align="right">
                        
                        <!-- <input type="button" class="btn btn-success btn-xs" @click="addCustomer1" value="Add New" /> -->
                    </div>
                    </div>

                    
                </div>
            </div>
            <table class="table table-striped table-hover table-bordered">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>Name <i class="fa fa-sort"></i></th>
                        <th>Address</th>
                        <th>City <i class="fa fa-sort"></i></th>
                        <th>Pin Code</th>
                        <th>Country <i class="fa fa-sort"></i></th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="row in allData">
                        <td>{{ row.id }}</td>
                        <td>{{ row.fname }} {{ row.lname}}</td>
                        <td>{{ row.address }}</td>
                        <td>{{ row.city }}</td>
                        <td>{{ row.pin }}</td>
                        <td>{{ row.country }}</td>
                        <td>
                             <a href="#" @click="updateData(row.id)" name="edit" class="edit" title="Edit" data-toggle="tooltip"><i class="material-icons">&#xE254;</i></a>
                            <a href="#" class="delete" title="Delete" name="delete" @click="deleteData(row.id)" data-toggle="tooltip"><i class="material-icons">&#xE872;</i></a>
                            
                        </td>
                    </tr>
                    <tr v-if="nodata">
								<td colspan="7" align="center" >No Data Found</td>
							</tr>
                </tbody>
            </table>
         
        </div>
    </div> 

    <div>
    <div class="form-group">
           <label>Enter First Name</label>
           <input type="text" class="form-control" v-model="fname" />
          </div>
          <div class="form-group">
           <label>Enter Last Name</label>
           <input type="text" class="form-control" v-model="lname" />
          </div>
          <div class="form-group">
           <label>Enter Address</label>
           <input type="text" class="form-control" v-model="address" />
          </div>
          <div class="form-group">
           <label>Enter City</label>
           <input type="text" class="form-control" v-model="city" />
          </div>
          <div class="form-group">
           <label>Enter Country</label>
           <input type="text" class="form-control" v-model="country" />
          </div>
          <div class="form-group">
           <label>Enter Pin no.</label>
           <input type="text" class="form-control" v-model="pin" />
          </div>
          <div align="center">
          <input type="hidden" id="currentCustomerid" v-model="id"/>
           <!-- <input type="hidden" v-model="hiddenId" /> -->
           <input type="button" class="btn btn-success btn-xs" value="ADD New Customer" @click="addCustomer" />
           <input type="button" class="btn btn-success btn-xs" value="Update Customer" @click="updateButton" />
           <hr>
          </div>
    
    </div>

</div>
</body>
</html>


<script>

var application = new Vue({
    el:'#crudApp',
    data:{
        allData:'',
        fname:'',
        lname:'',
        address:'',
        city:'',
        pin:'',
        country:'',
        id: '',
        nodata:false,
        query:''
    },

    methods:{
        
        fetchAllData:function(){
            axios.post('action.php',{
                action:'fetchall'
            }).then(function(response){

                if(response.data.length!=null){
                    application.allData = response.data;
                }
               
            });
        },

        addCustomer:function(){
            axios.post('action.php',{
                action:'addCustomerData',
                fname:application.fname,
                lname:application.lname,
                city:application.city,
                pin:application.pin,
                address:application.address,
                country:application.country
            }).then(function(response){
                application.fetchAllData();
                application.cleanForm();
                alert(response.data.message);
            });
        },
        deleteData:function(id){
            if(confirm("Are You sure want to remove this customer data?")){
                axios.post('action.php',
                {
                    action:'deleteCustomerDetails',
                    id:id
                }).then(function(response){
                    application.fetchAllData();
                    alert(response.data.message);
                });
            }
        },
        updateData:function(id){
            axios.post('action.php',{
                action:'fetchById',
                id:id
            }).then(function(response){
                application.id = response.data.id;
                application.fname=response.data.fname;
                application.lname=response.data.lname;
                application.city=response.data.city;
                application.address=response.data.address;
                application.country=response.data.country;
                application.pin=response.data.pin;
            }); 
        },
        updateButton:function(){
            axios.post('action.php',{
                action:'updateDetails',
                id:application.id,
                fname:application.fname,
                lname:application.lname,
                city:application.city,
                pin:application.pin,
                address:application.address,
                country:application.country
            }).then(function(response){
                application.fetchAllData();
                application.cleanForm();
                alert(response.data.message);
            });
        },
        searchData:function(){
            // alert(application.queryValue)
			axios.post('action.php', {
                action:'searchData',
                query:application.queryValue
			}).then(function(response){
				if(response.data.length > 0)
				{
					application.allData = response.data;
					application.nodata = false;
				}
				else
				{
					application.allData = '';
					application.nodata = true;
				}
			});
		},
        cleanForm:function(){
            application.fname=null,
            application.lname = null,
            application.country = null,
            application.city=null,
            application.address=null,
            application.id=null,
            application.pin=null
        }
    },
    created:function(){
  this.fetchAllData();
 }
});

</script>