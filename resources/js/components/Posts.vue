<template>

    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between pb-2 mb-2">
                <h5 class="card-title">All Posts Data</h5>
                <div>
                    <button class="btn btn-success" type="button" @click="this.$router.push('/posts/add')">New Post</button>
                </div>
            </div>

            <table class="table table-hover table-sm">
                <thead class="bg-dark text-light">
                <tr>
                    <th width="50" class="text-center">#</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th class="text-center" width="120">Image</th>
                    <th class="text-center" width="200">Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="text-center">index</td>
                    <td>post.name</td>
                    <td>post.description</td>
                    <td class="text-center">
                        <div >
                            <img alt="post-img" width="100">
                        </div>
                    </td>
                    <td class="text-center">
                        <router-link :to="{name:'editpost'}" class="btn btn-warning">Edit</router-link>
                        <button class="btn btn-danger">Delete</button>
                    </td>
                </tr>
                </tbody>
            </table>


        </div>
    </div>


</template>

<script>
export default {
    data() {
        return {

        }
    },
    created() {

    },
    methods: {

    }
}

</script>
