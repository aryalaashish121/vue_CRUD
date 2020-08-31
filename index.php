<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Simple Data Table</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="css/style.css" />
<link rel="stylesheet" href="css/screen.css" />

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script type="text/javascript" src="jquery.validate.js"></script>

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
                        <th>email <i class="fa fa-sort"></i></th>
                        <th>phone Code</th>
                        <th>profile <i class="fa fa-sort"></i></th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="row in allData">
                        <td>{{ row.id }}</td>
                        <td>{{ row.fname }} {{ row.lname}}</td>
                        <td>{{ row.address }}</td>
                        <td>{{ row.email }}</td>
                        <td>{{ row.phone }}</td>
                      
                        <td><img v-bind:src="('http://localhost/vueproject/'+row.profile)" alt="customer image" width="100" height="100"/></td>
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
    <p v-if="errors.length">
    <b>Please correct the following error(s):</b>
    <ul>
      <li class="text-danger" v-for="error in errors">{{ error }}</li>
    </ul>
  </p>

    <div class="form-group">
           <label>Enter First Name</label>
           <input type="text" id="fname" class="form-control" v-model="fname"/>
          </div>
          <div class="form-group">
           <label>Enter Last Name</label><h5>
           <input type="text" id="lname" class="form-control" v-model="lname" />
          </div>
          <div class="form-group">
           <label>Enter Address</label>
           <input type="text" class="form-control" v-model="address"/>
          </div>
          <div class="form-group">
           <label>Enter email</label>
           <input type="email" class="form-control" v-model="email"/>
          </div>
          
          <div class="form-group">
           <label>Enter phone no.</label>
           <input type="text"  class="form-control" v-model="phone" maxlength="10" minlength="10"/>
          </div>

          <div class="form-group">
           <label>Enter profile</label>
           <input type="file" class="form-control"  ref="profile" id="profile"  @change="uploadImage" />
           <input type="hidden" class="form-control" v-model="imageName"/>
         </div>
          </div>

          <div align="center">
          <input type="hidden" id="currentCustomerid"/>
           <!-- <input type="hidden" v-model="hiddenId" /> -->
           <input type="button" class="btn btn-success btn-xs" value="ADD New Customer" @click="addCustomer($event)" />
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
        allData:'',imageName:'',fname:'',lname:'', address:'',email:'',phone:'',profile:'',id: '',stringVal:'',
        nodata:false, query:'',queryValue:'',
         errors:'',phoneVal:''
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
            application.formValidation();
            axios.post('action.php',{
                action:'addCustomerData',
                fname:application.fname,
                lname:application.lname,
                email:application.email,
                phone:application.phone,
                address:application.address,
                profile:application.imageName
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
                application.email=response.data.email;
                application.address=response.data.address;
                //application.profile=response.data.profile;
                application.imageName=response.data.profile;
                application.phone=response.data.phone;
            }); 
        },
        updateButton:function(){
            if(application.formValidation()){
            axios.post('action.php',{
                action:'updateDetails',
                id:application.id,
                fname:application.fname,
                lname:application.lname,
                email:application.email,
                phone:application.phone,
                address:application.address,
                profile:application.imageName
            }).then(function(response){
                application.fetchAllData();
                application.cleanForm();
                alert(response.data.message);
            });
        }
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
            application.profile = null,
            application.email=null,
            application.address=null,
            application.id=null,
            application.phone=null,
            application.imageName=null
        },
        uploadImage:function(){
            application.profile = application.$refs.profile.files[0];

            var formData = new FormData();

        formData.append('file', application.profile);

        axios.post('uploadimage.php',  formData, {
        header:{
        'Content-Type' : 'multipart/form-data'
        }
        }).then(function(response){
            if(response.data.image ==''){
                alert(response.data.message);
                application.profile=null;
            }
            else{
                    application.imageName = response.data.image;
            }

        }).catch(function (error) {
           console.log(error);
       });


        },
        formValidation:function(error){
            if(this.fname && this.lname && this.email && this.address && this.imageName && this.phone){
                return true;
                
            }
            this.errors = [];
            stringVal = /^[a-zA-Z]+$/;
            phoneVal = /^\d{10}$/;
                //name errors
            if(!this.fname){
                this.errors.push('First name required');
            }else if(this.fname == stringVal){
                this.errors.push('Special Character are not allowed');
            }
                // last name errors
            if(!this.lname){
                this.errors.push('Last name Required');
            }
                //email errors
            if (!this.email) {
                 this.errors.push('Please enter a vaild email..');
                }
            else if (!this.validEmail(this.email)) {
                    this.errors.push('Your Email address is not vaild! Enter vaild address..');
            }
                //address
            if(!this.address){
                this.errors.push('Please enter your address');
            }
                //phone errors
            if(!this.phone){
                this.errors.push('Please enter your phone no');
            }else if(this.phone.length!=10){
                this.errors.push('Enter phone number is not vaild! Phone number must be of only 10 digit.')
            }
            if(!(this.phone).match(phoneVal)){
                this.errors.push("Error phone number! Phone number does not contain letters.")
            }

            if(!this.imageName){
                this.errors.push('Please Select Customer Image');
            }
           
             error.preventDefault();

            
        },
        
        validEmail: function (email) {
             var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(email);

        }
       

    },
    created:function(){
  this.fetchAllData();
 }
});

</script>