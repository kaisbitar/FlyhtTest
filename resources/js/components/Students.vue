

<template>
    <div>
        <!-- Options Buttons -->
        <button type="button" class="btn btn-primary mb-3 bigbtn" @click="showMe('add')">Add New</button>
        <button type="button" class="btn btn-success mb-3 bigbtn" @click="showMe('update')">Update</button>
        <button type="button" class="btn btn-secondary mb-3 bigbtn" @click="showMe('sort')">Sort By Status</button>
        <button type="button" class="btn btn-warning mb-3 bigbtn" @click="showMe('showDrDr')">Drag And Drop</button>


        <!-- Pagination -->
        <nav aria-label="Page navigation example" v-if="paginationNav">
            <ul class="pagination">
                <div class="form-group perListForm"><label for="phone">Per List:</label>   <br>         
                <input class="form-control mb-2 mr-sm-2 mb-sm-0" type="number" min="6" id="perListInput" v-model="perList" @input="fetchStudents()"> </div>
                <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item">
                    <a class="page-link" href="#" @click="fetchStudents(pagination.current_page, perList, 'prev')">Previous</a>
                </li>
                <li class="page-item disabled"><a class="page-link text-dark" href="#">
                    Page {{ pagination.current_page}} of {{ pagination.last_page }}</a>
                </li>
                <li v-bind:class="[{disabled: !pagination.next_page_url}]" class="page-item">
                    <a class="page-link" href="#" @click="fetchStudents(pagination.current_page, perList, 'next')">Next</a>
                </li>
            </ul>
        </nav>
        


        <!-- add/edit Student -->
        <div class="row" v-if="showForm">
            <form @submit.prevent="addStudent()" class="mb-3 ml-3">
                <div class="form-group">
                    <label for="firstName">First Name:</label>
                    <input type="text" class="form-control mb-2" placeholder="First Name" v-model="student.firstName">
                
                    <label for="lastName">Last Name:</label>
                    <input type="text" class="form-control mb-2" placeholder="Last Name" v-model="student.lastName">
                
                    <label for="phone">Phone:</label>
                    <input type="text" class="form-control mb-2" placeholder="Phone" v-model="student.phone">
                
                    <label for="status">Status:</label>
                    <input type="text" class="form-control mb-2" placeholder="Status" v-model="student.status" disabled>
                    <v-select :options="['Active', 'Dropped', 'Delinquent']" @input="setSelectedStatus"></v-select>
                </div>
                <div class="row submitButtons"> 
                    <button type="submit" class="btn btn-success mr-1">Save</button>
                    <button class="btn btn-danger" @click="resetForm('cancel')">Cancel</button>
                </div>
            </form>
            <div class="mb-3 ml-5">
                <h1>{{saveType}}</h1>
                <h1>
                    <span v-if="student.firstName != ''" class="badge badge-info">{{student.firstName}}</span>
                    <span v-if="student.lastName != ''" class="badge badge-info"> {{student.lastName}}</span>
                    <span v-if="student.phone != ''" class="badge badge-info">{{student.phone}}</span>
                    <span v-if="student.status != ''" class="badge badge-info"> {{student.status}}</span>
                </h1>
            </div>
        </div>

        <!-- Sortable Table -->
        <vue-good-table  v-if="sortShow" id="tableGrouped" :columns="columns" :rows="students"></vue-good-table>

        <!-- <h2>Students</h2> -->
        <div v-if="updateShow" class="row justify-content-center">
            <div v-for="(student, index) in students" v-bind:key="index">
                <draggable class="list-group" :list="students" group="people">
                    <div class="col-md-12 mb-3" >
                        <div class="card-group">
                            <div class="card">
                                <div class="card-body">
                                    <img class="card-img-top" src="https://leics-fire.gov.uk/wp-content/uploads/2017/04/person-placeholder-300x300.png" alt="Card image cap">                                
                                    <div class="card-text pl-64 st-info">
                                        <h5 class="card-title">{{ student.firstName }} {{ student.lastName }}</h5>
                                        <p class="card-title">Student ID: {{ student.id }}</p>
                                        <p class="card-text" id="phone"><small class="text-muted"><p>{{ student.phone }}</p></small></p>
                                        <p class="card-text"><small id="status" v-bind:style="{ color: statusColor(student.status)}">
                                        <p>{{ student.status }}</p></small></p>
                                    </div>
                                    <button @click = "editStudent(student)" class="btn btn-warning">Edit</button>
                                    <button @click = "deleteStudent(student.id)" class="btn btn-danger">Delete</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </draggable>    
            </div>
        </div>

        <!-- Drag and Drop -->
        <Test :showDrDr="showDrDr"></Test>
        
    </div>
</template>


<style scoped>
    .card-img-top {
        width: 19%;
    }
    .st-info{
        margin-top: -55px;
        padding-left: 64px;
    }
    #status{
        text-transform: capitalize;
    }
    #phone{
        margin-top: -6px;
    }
    .form-group {
        margin-bottom: 1rem;
        width: 301px;
    }
    .submitButtons{
        margin-left: 1px;
    }
    #perListInput{
        width: 76px;
    }
    .perListForm{
        width: 93px;
        margin-top: -32px;
    }
    ul.pagination {
        margin-top: 24px;
    }
    #tableGrouped{
        margin-bottom: 59px;
    }
    .card-text.st-info {
        cursor: move;
    }
</style>

 

<script>
import Test from './DragDrop.vue';
export default {
    components:{
        Test
    },
    data() { 
        return {
            students: [],
            student: {
                id: '',
                firstName:'',
                lastName: '',
                phone:'',
                status:'',
                image:'',
                id:''
            },
            student_id: '',
            pagination: {},
            paginationNav: false,
            edit: false,
            options: ['s',  'a', 'd'],
            showForm: false,
            updateShow: false,
            sortShow: false,
            showDrDr: false,
            saveType: '',
            perList: 6,
            columns: [
                {
                    label: 'First Name',
                    field: 'firstName',
                },
                {
                    label: 'Last Name',
                    field: 'lastName', 
                    type: 'text',
                },
                {
                    label: 'Phone',
                    field: 'phone',
                    type: 'text',
                },
                {
                    label: 'Status',
                    field: 'status',
                    type: 'text',
                    filterOptions: {
                        enabled: true, // enable filter for this column
                        placeholder: 'Choose Status', // placeholder for filter input
                        filterValue: '',
                        filterFn: this.columnFilterFn, //custom filter function that
                        trigger: 'enter',  filterDropdownItems: ['active', 'dropped', 'delinquent']
                },
               
            }
        ],
            editable: true,
        }
    },

    created() {
        this.fetchStudents();
    },

    methods: {
        resetForm(type){
            if(type == 'addNew'){
                this.showForm = true;
                this.saveType = 'Adding a New Student';            
            }
            else if(type == 'cancel'){
                this.showForm = false;
                this.updateShow = true;
                this.paginationNav = true;
            }
            this.edit = false
            this.student.id = '';
            this.student.student_id = '';
            this.student.firstName = '';
            this.student.lastName = '';
            this.student.phone = '';
            this.student.status = '';        
            this.selected = 'undefined';    
        },
        scrollToTop() {
                window.scrollTo(0,0);
        },
        fetchStudents(page, perList, direction) { 
            let vm = this;
            let page_url = '';
            if(page == undefined){
                page = 1;
            }           
            else if(direction){
                if(direction == 'next'){
                    page = this.pagination.current_page + 1 
                }
                else if(direction == 'prev'){
                    page = this.pagination.current_page - 1
                }

            }
            page_url ='api/students/'+ this.perList + '?page=' + page;
            fetch(page_url)
                .then(res => res.json())
                .then(res => {
                    this.students = res.data;
                    vm.makePagination(res.meta, res.links);
                })
                .catch(err => console.log(err));
        },
        makePagination(meta, links, per_page){
            let pagination = {
                current_page: meta.current_page,
                last_page: meta.last_page,
                next_page_url: links.next,
                prev_page_url: links.prev,
                current_page: meta.current_page,
                first_page: links.first
            }                
            this.per_page= meta.per_page
            this.pagination = pagination
        },
        deleteStudent(id) {
            if(confirm('Are You Sure?')) {
                fetch(`api/student/${id}`, {
                    method: 'delete'
                })
                .then(res => res.json())
                .then(data => {
                    alert('Student Removed');
                    this.fetchStudents();                    
                })
                .catch(err => console.log(err))
            }
        },
        addStudent(student_id) {                
            if(this.student.status == ''){
                this.student.status = 'Active';                
            }
            if(this.student.firstName == ''){
                this.student.firstName = 'Not specified';                
            }
            if(this.student.lastName == ''){
                this.student.lastName = 'Not specified';                
            }
            if(this.student.phone == ''){
                this.student.phone = 'Not specified';                
            }
            if(this.edit === false) {
                //add
                fetch('api/student', {
                    method: 'post',
                    body: JSON.stringify(this.student),
                    headers: {
                        'content-type': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    alert('Student Added');
                    this.fetchStudents();
                })
                .catch(err => console.log( err ))
            }
            else {
                //Update
                fetch('api/student', {
                    method: 'put',
                    body: JSON.stringify(this.student),
                    headers: {
                        'content-type': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    alert('Student Updated');
                    this.fetchStudents();
                })
                .catch(err => console.log( err ))
            }
            this.resetForm('cancel');
        },
        editStudent(student){
            this.showForm = true;
            this.updateShow = false;
            this.paginationNav = false;
            this.saveType = 'Editing:';
            this.scrollToTop();
            this.edit = true;
            this.student.id = student.id;
            this.student.student_id = student.id;
            this.student.firstName = student.firstName;
            this.student.lastName = student.lastName;
            this.student.phone = student.phone;
            this.student.status = student.status;
        },
        statusColor(status){
            if(status == 'active'){
                return 'green';
            }
            else if(status == 'dropped'){ 
                return 'red';
            }
            else if(status == 'delinquent'){
                return 'blue';
            }
        },
        setSelectedStatus(status){
            if(status !=null)
            this.student.status = status;
        },
        showMe(thisShow){
            if(thisShow == 'add'){ 
                this.resetForm('addNew');
                this.showForm = true;
                this.updateShow = false;
                this.sortShow = false;
                this.showDrDr = false;
                this.paginationNav = false;
            }
            else if(thisShow == 'update'){
                this.updateShow = true; 
                this.sortShow = false;
                this.showDrDr = false;
                this.showForm = false;
                this.paginationNav = true;
            }
            else if(thisShow == 'sort'){ 
                this.sortShow = true;
                this.updateShow = false;
                this.showDrDr = false;
                this.showForm = false;
                this.paginationNav = true;
            }
            else if(thisShow == 'showDrDr'){
                this.showDrDr = true;
                this.sortShow = false;
                this.updateShow = false;
                this.showForm = false;
                this.paginationNav = false;
            }
            this.fetchStudents();
        }          
    },
}
</script>
