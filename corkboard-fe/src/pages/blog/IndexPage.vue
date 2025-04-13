<template>
  <q-page padding>
    <div class="row items-center q-mb-md justify-between">
      <div class="col-4">
        <q-input
          v-model="search"
          debounce="300"
          label="Search Blogs..."
          @update:model-value="fetchBlogs"
        />
      </div>
      
      <div class="col-auto">
        <q-btn label="Create Blog" color="primary" @click="openAddModal" />
      </div>
    </div>


    <q-table
      :rows="filteredBlogs"
      :columns="columns"
      row-key="id"
      flat
      bordered
    >
      <template v-slot:body-cell-actions="props">
        <q-td align="right">
          <q-btn-group flat dense>
            <q-btn
              color="info"
              label="View"
              size="sm"
              @click="previewBlog(props.row)"
              aria-label="View"
            />

            <q-btn
              color="warning"
              icon="edit"
              size="sm"
              @click="editBlog(props.row)"
              :aria-label="'Edit'"
            />

            <q-btn
              :color="props.row.status === 'published' ? 'negative' : 'positive'"
              :icon="props.row.status === 'published' ? 'visibility_off' : 'visibility'"
              size="sm"
              @click="changeStatus(props.row)"
              :aria-label="props.row.status === 'published' ? 'Hide Blog' : 'Publish Blog'"
            />

            <q-btn
              color="red"
              icon="delete"
              size="sm"
              @click="archiveBlog(props.row)"
              aria-label="Delete"
            />
          </q-btn-group>

        </q-td>
      </template>
    </q-table>

    <BlogFormDialog
      v-model="isDialogOpen" 
      :selectedBlog="selectedBlog" 
      @close="closeDialog"
      @refresh="fetchBlogs"
    />
  </q-page>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router'
import BlogFormDialog from 'components/blog/FormDialog.vue';
import { api } from 'boot/axios';
import type { Blog } from 'src/types/blog';
import type { QTableColumn } from 'quasar';

const router = useRouter()
const blogs = ref([])
const search = ref('')
const isDialogOpen = ref(false)
const selectedBlog = ref(null)

const columns: QTableColumn[] = [
  {
    name: 'title',
    label: 'Title',
    field: 'title',
    align: 'left',
    headerClasses: 'text-center',
    classes: 'text-left'
  },
  {
    name: 'status',
    label: 'Status',
    field: 'formatted_status',
    align: 'left',
    headerClasses: 'text-center',
    classes: 'text-left',
    style: 'width: 15%;'
  },
  {
    name: 'created_at',
    label: 'Created At',
    field: 'formatted_created_at',
    align: 'left',
    headerClasses: 'text-center',
    classes: 'text-left',
    style: 'width: 15%;'
  },
  {
    name: 'actions',
    label: 'Actions',
    field: 'actions',
    align: 'right',
    headerClasses: 'text-center',
    sortable: false,
    style: 'width: 20%;'
  }
]

const fetchBlogs = async () => {
  try {
    const params = search.value ? { search: search.value } : {};
    const res = await api.get<{ blogs: Blog[] }>('/blogs', { params });
    blogs.value = res.data.blogs;
  } catch (error) {
    console.error('Error fetching blogs:', error);
  }
};

const filteredBlogs = computed(() => blogs.value)

const openAddModal = () => {
  console.log("open add modal")
  selectedBlog.value = null;
  isDialogOpen.value = true;
  console.log(selectedBlog.value)
  console.log(isDialogOpen.value)
}

const editBlog = (blog: Blog) => {
  selectedBlog.value = blog;
  isDialogOpen.value = true;
}

const changeStatus = async (blog: Blog) => {
  await api.patch(`/blogs/${blog.id}/status`);
  await fetchBlogs();
}

const archiveBlog = async (blog: Blog) => {
  await api.delete(`/blogs/${blog.id}`);
  await fetchBlogs();
}

const previewBlog = async (blog: Blog) => {
  await router.push({ name: 'BlogView', params: { id: blog.id } });
};

const closeDialog = () => {
  isDialogOpen.value = false;
  selectedBlog.value = null;
};

onMounted(() => {
  void fetchBlogs()
})
</script>