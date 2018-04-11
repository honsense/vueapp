<template>
    <div>
        <md-dialog :md-active="showReqform" @md-opened="multiSelection=[]">
            <md-toolbar>
                <md-dialog-title>Request Info</md-dialog-title>
                <span style="position:absolute; right:15px">
                    <md-button v-if="multiSelection.length==0" class="md-fab md-dense md-primary" @click="newObservation">
                        <md-icon>add</md-icon>
                    </md-button>
                    <md-button v-else class="md-fab md-dense md-primary" @click="approve">
                        <md-icon>edit</md-icon>
                    </md-button>
                </span>
            </md-toolbar>
            <div>
                <md-progress-bar md-mode="indeterminate" v-show="progress"></md-progress-bar>
            </div>
            <md-tabs md-dynamic-height>
                <md-tab md-label="Request">
                <md-field>
                    <label>Reference</label>
                    <md-input :value="reference" @input="$emit('update:reference', $event)"></md-input>
                </md-field>
                <md-field>
                    <label>Requester</label>
                    <md-input :value="requester" @input="$emit('update:requester', $event)"></md-input>
                </md-field>
                <md-field>
                    <label>Comments</label>
                    <md-input :value="comments" @input="$emit('update:comments', $event)"></md-input>
                </md-field>
                </md-tab>
                <md-tab md-label="Observations">
                    <md-table :value="filteredObs" md-card @md-selected="onSelect" md-fixed-header>
                        <!--<md-table-toolbar>
                        <h1 class="md-title"></h1>
                        </md-table-toolbar>-->
                        <md-table-empty-state md-label="No observations found" :md-description="`No observations found.`">
                            <md-button class="md-primary md-raised" @click="newObservation">Create New Observation</md-button>
                        </md-table-empty-state>

                        <md-table-row slot="md-table-row" slot-scope="{ item }" md-selectable="multiple">
                            <!-- <md-table-cell md-label="ID" md-sort-by="id" md-numeric>{{ item.Id }}</md-table-cell> -->
                            <md-table-cell md-label="Reference" md-sort-by="REFERENCE">{{ item.REFERENCE }}</md-table-cell>
                            <md-table-cell md-label="Observation" md-sort-by="ACTION">{{ item.OBSERVATION }}</md-table-cell>
                            <md-table-cell md-label="Action" md-sort-by="ACTION">{{ item.ACTIONS }}</md-table-cell>
                            <md-table-cell md-label="Response" md-sort-by="RESPONSE">{{ item.RESPONSE }}</md-table-cell>
                            <md-table-cell md-label="Reviewer" md-sort-by="REVIEWER">{{ item.REVIEWER }}</md-table-cell>
                            <md-table-cell md-label="" class="update"><md-button class="md-primary" @click="updateObs(item)">Update</md-button></md-table-cell>
                        </md-table-row>
                    </md-table>
                </md-tab>
            </md-tabs>
            <md-dialog-actions>
                <md-button class="md-primary" @click="closeForm">Close</md-button>
                <md-button class="md-primary" @click="postData">Save</md-button>
            </md-dialog-actions>
        </md-dialog>
    </div>
</template>

<script>
export default {
    name: 'reqview',
    data() {
        return{
            progress:false,
            multiSelection: [],
            activeObs: {},
        }
    },
    props: ['showObsform', 'showReqform','reference', 'requester', 'comments', 'observations', 'Id', 'mode', 'obsMode'],
    methods: {
        closeForm: function(){
            this.$emit('update:showReqform', false);
            //this.$emit('update:select', 0);
        },
        newObservation: function(){
            this.$emit('update:activeObs', {});
            this.$emit('update:obsMode', 'insert');
            this.$emit('update:showObsform', true);
            this.$emit('update:showReqform', false);
        },
        postData: function(){
            this.progress=true;
            console.log(this.Id, this.reference, this.requester, this.comments, this.mode);
            this.$http.post(
                "insert.php", {'Id':this.Id, 'mode':this.mode,'reference':this.reference, 'requester':this.requester, 'comments':this.comments}
            )
            .then(
                function(status){
                    if(status.data = 'Data Inserted...'){
                        this.$emit('update:showReqform', false);
                        this.$parent.refresh();
                        this.$emit('update:search', null)
                    }
                this.progress=false;
                console.log(status);
            });
        },
        onSelect: function(item){
            console.log(item); //update to allow editing if only one checkbox filled.
            this.multiSelection = item;
        },
        updateObs: function(item){
            // console.log(item);
            this.$emit('update:activeObs', item);
            this.$emit('update:obsMode', 'update');
            this.$emit('update:showObsform', true);
            this.$emit('update:showReqform', false);
        },
        approve: function(){
            this.progress=true;
            var ids = [];
            for(var x in this.multiSelection){
                ids.push(this.multiSelection[x].Id)
            }
            this.$http.post(
                "obsinsert.php", {'Id':ids, 'mode':'approve'}
            )
            .then(
                function(status){
                    console.log(status);
                    if(status.data = 'Data Inserted...'){
                        this.$emit('update:showReqform', false);
                        this.$parent.refresh();
                        this.$emit('update:search', null)
                    }
                 this.progress=false;
            });
        },
    },
    computed:{
        filteredObs: function(){
            return this.observations.filter((observation) => {
                return observation.REQUEST_ID == this.Id
            });
        },
    }
}
</script>
<style>
    .update{
        text-align: right;
    }
</style>
