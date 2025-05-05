<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import Button from '@/components/ui/button/Button.vue';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import DateRangePicker from '@/components/DateRangePicker.vue'
// import Overview from '@/components/Overview.vue'


const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

const props = defineProps({
    'lost_items': Number,
    'found_items': Number
});

</script>

<template>

    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">

            <div
                class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                <div class="flex-1 space-y-4 p-8 pt-6">
                    <div class="flex flex-col sm:flex-row items-center justify-end space-y-2 space-x-2 sm:space-y-0">
                            <DateRangePicker/>
                            <Button>View</Button>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                        <Card>
                            <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                                <CardTitle class="text-sm font-medium">
                                    Total Lost Items
                                </CardTitle>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" strokeLinecap="round" strokeLinejoin="round" strokeWidth="2"
                                    class="h-4 w-4 text-muted-foreground">
                                    <path d="M22 12h-4l-3 9L9 3l-3 9H2" />
                                </svg>
                            </CardHeader>
                            <CardContent>
                                <div class="text-2xl font-bold">
                                    {{ props.lost_items }} items
                                </div>
                                <p class="text-xs text-muted-foreground">
                                    +20.1% from last month
                                </p>
                            </CardContent>
                        </Card>
                        <Card>
                            <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                                <CardTitle class="text-sm font-medium">
                                    Total Found Items
                                </CardTitle>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" strokeLinecap="round" strokeLinejoin="round" strokeWidth="2"
                                    class="h-4 w-4 text-muted-foreground">
                                    <path d="M22 12h-4l-3 9L9 3l-3 9H2" />
                                </svg>
                            </CardHeader>
                            <CardContent>
                                <div class="text-2xl font-bold">
                                    {{ props.found_items }} items
                                </div>
                                <p class="text-xs text-muted-foreground">
                                    +180.1% from last month
                                </p>
                            </CardContent>
                        </Card>
                        <Card>
                            <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                                <CardTitle class="text-sm font-medium">
                                    Matches Made
                                </CardTitle>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" strokeLinecap="round" strokeLinejoin="round" strokeWidth="2"
                                    class="h-4 w-4 text-muted-foreground">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                    <circle cx="9" cy="7" r="4" />
                                    <path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" />
                                </svg>
                            </CardHeader>
                            <CardContent>
                                <div class="text-2xl font-bold">
                                    +12,234
                                </div>
                                <p class="text-xs text-muted-foreground">
                                    +19% from last month
                                </p>
                            </CardContent>
                        </Card>
                        <Card>
                            <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                                <CardTitle class="text-sm font-medium">
                                    Pending Verifications
                                </CardTitle>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" strokeLinecap="round" strokeLinejoin="round" strokeWidth="2"
                                    class="h-4 w-4 text-muted-foreground">
                                    <path d="M22 12h-4l-3 9L9 3l-3 9H2" />
                                </svg>
                            </CardHeader>
                            <CardContent>
                                <div class="text-2xl font-bold">
                                    88 pending
                                </div>
                                <p class="text-xs text-muted-foreground">
                                    +201 since last hour
                                </p>
                            </CardContent>
                        </Card>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-7">
                        <Card class="col-span-4">
                            <CardHeader>
                                <CardTitle>Overview</CardTitle>
                            </CardHeader>
                            <CardContent class="pl-2">
                                <!-- <Overview /> -->
                            </CardContent>
                        </Card>
                        <Card class="col-span-3">
                            <CardHeader>
                                <CardTitle>Recent Matches</CardTitle>
                                <CardDescription>
                                    made 265 matches this month.
                                </CardDescription>
                            </CardHeader>
                            <CardContent>
                                <!-- <RecentSales /> -->
                            </CardContent>
                        </Card>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
