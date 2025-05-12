<script setup lang="ts">
import { computed } from 'vue';
import { ScrollArea } from '@/components/ui/scroll-area'
import { Separator } from '@/components/ui/separator'
import {Tabs,TabsContent,TabsList,TabsTrigger} from '@/components/ui/tabs'
// import {Select} from '@/components/ui/select'
import { Input } from '@/components/ui/input'
import { Search } from 'lucide-vue-next'

// import PlusCircledIcon from '~icons/radix-icons/plus-circled'
import ItemsList from '@/components/ItemsList.vue'
import ItemEmptyPlaceholder from '@/components/ItemPlaceholder.vue';

import Sidebar from '@/components/Sidebar.vue'

import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem} from '@/types';

interface PaginatedItems{
  current_page: number;
  last_page: number;
}

interface Props {
    // mustVerifyEmail: boolean;
    status?: string;
    categories : object;
    items : object;
    itemsPaginated : PaginatedItems
}

const props = defineProps<Props>();

const reachedEnd = computed(()=>{
    return props.itemsPaginated.current_page >= props.itemsPaginated.last_page
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Items',
        href: '/items',
    },
    {
        title: 'All Items',
        href: '/items',
    },
];
</script>

<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <Head title="Lost-Items" />
    <!-- <Menu /> -->
    <div class="border-t">
      <div class="bg-background">
        <div class="grid lg:grid-cols-5">
          <Sidebar :categories="categories" class="hidden lg:block" />
          <div class="col-span-3 lg:col-span-4 lg:border-l">
            <div class="h-full px-4 py-6 lg:px-8">
              <Tabs default-value="all" class="h-full space-y-6">
                <div class="space-between flex items-center justify-between">
                  <TabsList>
                    <TabsTrigger value="all" class="relative cursor-pointer">
                      All
                    </TabsTrigger>
                    <TabsTrigger value="approved" class="cursor-pointer"> Approved </TabsTrigger>
                    <TabsTrigger value="pendingApproval" class="cursor-pointer">
                      Pending Approval
                    </TabsTrigger>
                  </TabsList>
                  <div class="lg:hidden"></div>
                  <div class="hidden lg:block">
                    <div class="ml-auto mr-4 relative">
                      <Input
                        id="search"
                        type="text"
                        placeholder="Search..."
                        class="pl-10"
                      />
                      <span
                        class="absolute start-0 inset-y-0 flex items-center justify-center px-2"
                      >
                        <Search class="size-6 text-muted-foreground" />
                      </span>
                    </div>
                  </div>
                </div>
                <TabsContent value="all" class="border-none p-0 outline-none">
                  <div class="flex items-center justify-between">
                    <div class="space-y-1">
                      <h2 class="text-2xl font-semibold tracking-tight">All Items</h2>
                      <p class="text-sm text-muted-foreground">
                        Including Lost and Found Items.
                      </p>
                    </div>
                  </div>
                  <Separator class="my-4" />
                  <div class="relative">
                    <ScrollArea>
                      <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 space-x-2">
                        <ItemsList
                          v-for="(item, i) in props.items"
                          :key="i"
                          :item="item"
                          class="w-[150px] lg:w-[250px]"
                          aspect-ratio="square"
                          :width="250"
                          :height="330"
                        />
                        <WhenVisible 
                          :always="!reachedEnd"
                          :params="{
                            data: {
                              page: itemsPaginated.current_page + 1
                            },
                            only: ['items', 'itemsPaginated'],
                          }"
                        >
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
                <TabsContent
                  value="approved"
                  class="h-full flex-col border-none p-0 data-[state=active]:flex"
                >
                  <div class="flex items-center justify-between">
                    <div class="space-y-1">
                      <h2 class="text-2xl font-semibold tracking-tight">Select an Item to Analyse</h2>
                      <p class="text-sm text-muted-foreground">
                        Sensitive information Hidden
                      </p>
                    </div>
                  </div>
                  <Separator class="my-4" />
                  <ItemEmptyPlaceholder />
                </TabsContent>
              </Tabs>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
