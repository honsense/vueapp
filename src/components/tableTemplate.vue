<template>
    <div id="app">
        <md-table v-model="searched" md-card md-sort="name" md-sort-order="asc" >
            <md-table-toolbar>
                <h1 class="md-title">Review Requests</h1>
                    <div class="md-toolbar-section-start">
                    </div>

                <md-field md-clearable class="md-toolbar-section-end">
                    <md-input placeholder="Enter your reference" v-model="search" @input="searchUpdate"/>
                </md-field>
            </md-table-toolbar>
            <div>
                <md-progress-bar md-mode="indeterminate" v-show="progress"></md-progress-bar>
            </div>

            <md-table-empty-state md-label="No reference found" :md-description="`No matching reference found.`">
                <md-button class="md-primary md-raised" @click="newRequest">Create New Request</md-button>
            </md-table-empty-state>

            <md-table-row slot="md-table-row" slot-scope="{ item }" md-selectable="single" @click="onSelect(item)">
                <md-table-cell md-label="Reference" md-sort-by="REFERENCE">{{ item.REFERENCE }}</md-table-cell>
                <md-table-cell md-label="Requester" md-sort-by="REQUESTER">{{ item.REQUESTER }}</md-table-cell>
                <md-table-cell md-label="Comments" md-sort-by="COMMENTS">{{ item.COMMENTS }}</md-table-cell>
                <md-table-cell md-label="Date Requested" md-sort-by="DATE_REQUESTED">{{ item.DATE_REQUESTED }}</md-table-cell>
            </md-table-row>
        </md-table>
        <RequestForm :showObsform.sync="showObsform" :showReqform.sync="showReqform" :Id.sync="Id" :reference.sync="reference" :requester.sync="requester" :comments.sync="comments" 
                     :observations="observations" :mode="mode" :activeObs.sync="activeObs" :obsMode.sync="obsMode"></RequestForm>
        <ObservationForm v-if="showObsform" :showObsform.sync="showObsform" :showReqform.sync="showReqform" :reference.sync="reference" :activeObs.sync="activeObs" :obsMode.sync="obsMode"></ObservationForm>
    </div>
</template>

<script>
import RequestForm from '@/components/RequestForm'
import ObservationForm from '@/components/ObservationForm'

const toLower = text => {
    return text.toString().toLowerCase()
}

const searchByRef = (items, term) => {
    if (term) {
        return items.filter(item => toLower(item.REFERENCE).includes(toLower(term)))
    }
    return items
}

export default {
    name: 'sourceSelection',
    components:{
        RequestForm,
        ObservationForm
    },
    data() {
        return{
            sources: [],
            observations: [],
            source: '',
            showReqform: false,
            showObsform: false,
            reference: '',
            requester: '',
            comments: '',
            Id: '',
            progress: true,
            search: null,
            searched: [],
            mode: '',
            activeObs: {},
            obsMode: '',
        }
    },
    methods: {
        searchUpdate: function() {
            this.searched = searchByRef(this.sources, this.search);
        },
        onSelect: function(item){
            this.showReqform = true;
            this.Id = item.Id;
            this.reference = item.REFERENCE;
            this.requester = item.REQUESTER;
            this.comments = item.COMMENTS;
            this.mode = 'update';
        },
        newRequest: function() {
            this.showReqform = true;
            this.reference = this.search;
            this.requester = '';
            this.comments = '';
            this.Id = '';
            this.mode = 'insert';
        },
        refresh: function (obs) {
            this.$http.get('select.php')
            .then(function(response){
            this.sources = response.body;
            this.searched = this.sources;
            this.search = null;
            });
            this.$http.get('obsselect.php')
            .then(function(response){
            this.observations = response.body;
            this.progress=false
            })
            if(obs) {
                this.showObsform = false;
                this.showReqform = true;
            }
        }
    },
    created: function () {
        this.$http.get('select.php')
        .then(function(response){
        this.sources = response.body;
        this.searched = this.sources;
        });
        this.$http.get('obsselect.php')
        .then(function(response){
        this.observations = response.body;
        this.progress=false;
        })
    },
    computed:{
        filteredItems: function(){
            return this.sources.filter((item)=>{
                return item.REFERENCE.match(this.search)
            });
        }
    }
}
</script>

<style scoped>

        #app{
            margin-top: 60px;
            margin-left: 30px;
            margin-right: 30px;
            margin-bottom: 60px;
        }
</style>