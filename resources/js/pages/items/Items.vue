<script setup lang="ts">
import { computed } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import { ScrollArea } from '@/components/ui/scroll-area'
import { Separator } from '@/components/ui/separator'
import ItemPlaceholder from '@/components/ItemPlaceholder.vue'
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs'
// import {Select} from '@/components/ui/select'
import { Input } from '@/components/ui/input'
import { Search } from 'lucide-vue-next'

// import PlusCircledIcon from '~icons/radix-icons/plus-circled'
import ItemsList from '@/components/ItemsList.vue'
// import ItemEmptyPlaceholder from '@/components/ItemPlaceholder.vue';

import Sidebar from '@/components/Sidebar.vue'

import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';

interface PaginatedItems {
  current_page: number;
  last_page: number;
}

interface Props {
  // mustVerifyEmail: boolean;
  status?: string;
  categories: Array<{ id: string; name: string }>;
  items: object;
  itemsPaginated: PaginatedItems;
  searchTerm?: string;
}

const props = defineProps<Props>();

const reachedEnd = computed(() => {
  return props.itemsPaginated.current_page >= props.itemsPaginated.last_page
});

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Items',
    href: '/items',
  }
];

const params = route().params;

const filter = (query: number) => {
  router.get(route('items.all'), { approval: query, postStatus: params.postStatus, category: params.category, search: form.search },
    { preserveState: true, preserveScroll: true })
}
const filterByPostType = (query: string ) => {
  router.get(route('items.all'), { postStatus: query, approval: params.approval, category: params.category, search: form.search },
    { preserveState: true, preserveScroll: true })
}
const filterByCategory = (query: string ) => {
  router.get(route('items.all'), { category: query, postStatus: params.postStatus, approval: params.approval, search: form.search },
    { preserveState: true, preserveScroll: true })
} 

const form = useForm({
  search: props.searchTerm,
});

const search = () => {
  router.get(route('items.all'), { search: form.search, approval: params.approval, postStatus: params.postStatus, category: params.category },
    { preserveState: true, preserveScroll: true })
}
const resetSearch = () => {
  form.search = '';
}
</script>

<template>
  <AppLayout :breadcrumbs="breadcrumbs">

    <Head title="Lost-Items" />
    <!-- <Menu /> -->
    <div class="border-t">
      <div class="bg-background">
        <div class="grid lg:grid-cols-5">
          <Sidebar   :categories="categories" 
          :current-post-type="params.postStatus"
          :current-category="params.category"
          :current-approval="params.approval" @filterByPostType="(e) => filterByPostType(e)"
            @filterByCategory="(e) => filterByCategory(e)" class="hidden lg:block" />
          <div class="col-span-3 lg:col-span-4 lg:border-l">
            <div class="h-full px-4 py-6 lg:px-8">
              <Tabs default-value="all" class="h-full space-y-6">
                <div class="space-between flex items-center justify-between">
                  <TabsList>

                    <TabsTrigger value="all" class="relative cursor-pointer">
                      <Link :href="route('items.all', { ...params, approval: null, postStatus: null, category: null, page: null, search: null })"
                        @click="resetSearch"
                        class="relative cursor-pointer" 
                        preserve-scroll
                        preserve-state>
                      All
                      </Link>
                    </TabsTrigger>
                    <TabsTrigger value="approved" @click="filter(1)" class="cursor-pointer">
                      Approved
                    </TabsTrigger>
                    <TabsTrigger value="pending" @click="filter(0)" class="cursor-pointer">
                      Pending Approval
                    </TabsTrigger>
                    <TabsTrigger value="test" @click="filter(0)" class="cursor-pointer">
                      Test
                    </TabsTrigger>
                  </TabsList>
                  <div class="lg:hidden"></div>
                  <div class="hidden lg:block">
                    <form @submit.prevent="search" class="flex items-center">
                      <div class="ml-auto mr-4 relative">
                        <Input id="search" type="text" v-model="form.search" placeholder="Search..." class="pl-10" />
                        <span class="absolute start-0 inset-y-0 flex items-center justify-center px-2">
                          <Search class="size-6 text-muted-foreground" />
                        </span>
                      </div>
                    </form>
                  </div>
                </div>
                <TabsContent value="all" class="border-none p-0 outline-none">
                  <div class="flex items-center justify-between">
                    <div class="space-y-1">
                      <h2 class="text-2xl font-semibold tracking-tight">All Items</h2>
                      <p class="text-sm text-muted-foreground">
                        Displaying all reported Items.
                      </p>
                    </div>
                  </div>
                  <Separator class="my-4" />
                  <div class="relative">
                    <ScrollArea>
                      <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 space-x-2">
                        <ItemsList v-for="(item, i) in props.items" :key="i" :item="item" class="w-[180px] lg:w-[250px]"
                          aspect-ratio="square" :width="250" :height="330" />
                        <WhenVisible :always="!reachedEnd" :params="{
                          data: {
                            page: itemsPaginated.current_page + 1
                          },
                          only: ['items', 'itemsPaginated'],
                        }">
                          <template #fallback>
                            <span>
                              Loading...
                            </span>
                          </template>
                        </WhenVisible>
                      </div>
                    </ScrollArea>
                  </div>
                </TabsContent>
                <TabsContent value="approved" class="h-full flex-col border-none p-0 data-[state=active]:flex">
                  <div class="flex items-center justify-between">
                    <div class="space-y-1">
                      <h2 class="text-2xl font-semibold tracking-tight">Verified Reports</h2>
                      <p class="text-sm text-muted-foreground">
                        These items have been verified and approved.
                      </p>
                    </div>
                  </div>
                  <Separator class="my-4" />
                  <div class="relative">
                    <ScrollArea>
                      <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 space-x-2">
                        <ItemsList v-for="(item, i) in props.items" :key="i" :item="item" class="w-[150px] lg:w-[250px]"
                          aspect-ratio="square" :width="250" :height="330" />
                        <WhenVisible :always="!reachedEnd" :params="{
                          data: {
                            page: itemsPaginated.current_page + 1
                          },
                          only: ['items', 'itemsPaginated'],
                        }">
                          <template #fallback>
                            <span>
                              Loading...
                            </span>
                          </template>
                        </WhenVisible>
                      </div>
                    </ScrollArea>
                  </div>
                </TabsContent>
                <TabsContent value="pending" class="h-full flex-col border-none p-0 data-[state=active]:flex">
                  <div class="flex items-center justify-between">
                    <div class="space-y-1">
                      <h2 class="text-2xl font-semibold tracking-tight">Under Review</h2>
                      <p class="text-sm text-muted-foreground">
                        Items awaiting review and approval.
                      </p>
                    </div>
                  </div>
                  <Separator class="my-4" />
                  <div class="relative">
                    <ScrollArea>
                      <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 space-x-2">
                        <ItemsList v-for="(item, i) in props.items" :key="i" :item="item" class="w-[150px] lg:w-[250px]"
                          aspect-ratio="square" :width="250" :height="330" />
                        <WhenVisible :always="!reachedEnd" :params="{
                          data: {
                            page: itemsPaginated.current_page + 1
                          },
                          only: ['items', 'itemsPaginated'],
                        }">
                          <template #fallback>
                            <span>
                              Loading...
                            </span>
                          </template>
                        </WhenVisible>
                      </div>
                    </ScrollArea>
                  </div>
                </TabsContent>
                <TabsContent value="test"class="h-full flex-col border-none p-0 data-[state=active]:flex">
                  <div class="flex items-center justify-between">
                    <div class="space-y-1">
                      <h2 class="text-2xl font-semibold tracking-tight">Select an Item to Analyse</h2>
                      <p class="text-sm text-muted-foreground">
                        Sensitive information Hidden
                      </p>
                    </div>
                  </div>
                  <Separator class="my-4" />
                  <ItemPlaceholder  />
                </TabsContent>
              </Tabs>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
