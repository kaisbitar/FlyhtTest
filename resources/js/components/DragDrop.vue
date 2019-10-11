<template>
  <div class="row" v-if="showDrDr">
    <div class="col-3">
      <h3> <span class="badge badge-success">Active</span></h3>
      <draggable class="list-group" :list="ActiveStudents" group="people" @change="makeActive">      
        <div  
          class="list-group-item"
          v-for="(student) in ActiveStudents"
          :key="student.id"
        >
            {{student.firstName}} {{student.lastName}}      
        </div>
      </draggable>
    </div>
    <div class="col-3">
      <h3><span class="badge badge-danger">Dropped</span> </h3>
      <draggable class="list-group" :list="DroppedStudents" group="people" @change="makeDropped" >
        <div
          class="list-group-item"
          v-for="(student, index) in DroppedStudents"
          :key="index"
        >
          {{student.firstName}} {{student.lastName}}      
        </div>
      </draggable>
    </div>
    <div class="col-3">
      <h3><span class="badge badge-info">Delinquent</span> </h3>
      <draggable class="list-group" :list="DelinquentStudents" group="people" @change="makeDel">
        <div
          class="list-group-item"
          v-for="(student, index) in DelinquentStudents"
          :key="index"
        >
          <div>{{ student.firstName }} {{ student.lastName}}</div>      
        </div>
      </draggable>
    </div>

  </div>
</template>
<script>
export default {
    props:['showDrDr'],
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
            } 
        }
    },
    methods: {
        fetchStudents() { 
        let page_url ='api/students/100';
        fetch(page_url)
            .then(res => res.json())
            .then(res => {
                this.students = res.data;
            })
            .catch(err => console.log(err));
        },
        makeActive: function(evt) {
            if(evt.added){ 
                evt.added.element.status = 'Active'
                this.student = evt.added.element
                this.changeStatus()        
            }
        },
        makeDropped: function(evt) {
            if(evt.added){ 
                evt.added.element.status = 'Dropped'
                this.student = evt.added.element
                this.changeStatus()        
            }
        },
        makeDel: function(evt) {
            if(evt.added){ 
                evt.added.element.status = 'Delinquent'
                this.student = evt.added.element
                this.changeStatus()        
            }
        },
        changeStatus() { 
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
        },
    },
    created() {
        this.fetchStudents();
    },
    computed: {
        ActiveStudents: function() {
            var activeStudents = this.students.filter(function(student) {
            if(student.status == 'Active')
                return student;
            });                
            return activeStudents;
        },
        DroppedStudents: function() {
            var droppedStudents = this.students.filter(function(student) {
            if(student.status == 'Dropped')
                return student;
            });                
            return droppedStudents; 
        },
        DelinquentStudents: function() {
            var delinquentStudents = this.students.filter(function(student) {
            if(student.status == 'Delinquent')
                return student;
            });                
            return delinquentStudents;
        }           
    }
};
</script>
