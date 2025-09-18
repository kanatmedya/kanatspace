                <!-- end footer section -->
            </div>
        </div>

        <script src="assets/js/alpine-collaspe.min.js"></script>
        <script src="assets/js/alpine-persist.min.js"></script>
        <script defer src="assets/js/alpine-ui.min.js"></script>
        <script defer src="assets/js/alpine-focus.min.js"></script>
        <script defer src="assets/js/alpine.min.js"></script>
        <script src="assets/js/custom.js"></script>
        <script defer src="assets/js/apexcharts.js"></script>
        <script src="assets/js/Sortable.min.js"></script>

        <script>
            // main section
            document.addEventListener('alpine:init', () => {
                Alpine.data('scrollToTop', () => ({
                    showTopButton: false,
                    init() {
                        window.onscroll = () => {
                            this.scrollFunction();
                        };
                    },

                    scrollFunction() {
                        if (document.body.scrollTop > 50 || document.documentElement.scrollTop > 50) {
                            this.showTopButton = true;
                        } else {
                            this.showTopButton = false;
                        }
                    },

                    goToTop() {
                        document.body.scrollTop = 0;
                        document.documentElement.scrollTop = 0;
                    },
                }));

                // theme customization
                Alpine.data('customizer', () => ({
                    showCustomizer: false,
                }));

                // sidebar section
                Alpine.data('sidebar', () => ({
                    init() {
                        const selector = document.querySelector('.sidebar ul a[href="' + window.location.pathname + '"]');
                        if (selector) {
                            selector.classList.add('active');
                            const ul = selector.closest('ul.sub-menu');
                            if (ul) {
                                let ele = ul.closest('li.menu').querySelectorAll('.nav-link');
                                if (ele) {
                                    ele = ele[0];
                                    setTimeout(() => {
                                        ele.click();
                                    });
                                }
                            }
                        }
                    },
                }));

                // header section
                Alpine.data('header', () => ({
                    init() {
                        const selector = document.querySelector('ul.horizontal-menu a[href="' + window.location.pathname + '"]');
                        if (selector) {
                            selector.classList.add('active');
                            const ul = selector.closest('ul.sub-menu');
                            if (ul) {
                                let ele = ul.closest('li.menu').querySelectorAll('.nav-link');
                                if (ele) {
                                    ele = ele[0];
                                    setTimeout(() => {
                                        ele.classList.add('active');
                                    });
                                }
                            }
                        }
                    },

                    notifications: [
                        {
                            id: 1,
                            profile: 'user-profile.jpeg',
                            message: '<strong class="text-sm mr-1">John Doe</strong>invite you to <strong>Prototyping</strong>',
                            time: '45 min ago',
                        },
                        {
                            id: 2,
                            profile: 'profile-34.jpeg',
                            message: '<strong class="text-sm mr-1">Adam Nolan</strong>mentioned you to <strong>UX Basics</strong>',
                            time: '9h Ago',
                        },
                        {
                            id: 3,
                            profile: 'profile-16.jpeg',
                            message: '<strong class="text-sm mr-1">Anna Morgan</strong>Upload a file',
                            time: '9h Ago',
                        },
                    ],

                    messages: [
                        {
                            id: 1,
                            image: '<span class="grid place-content-center w-9 h-9 rounded-full bg-success-light dark:bg-success text-success dark:text-success-light"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg></span>',
                            title: 'Congratulations!',
                            message: 'Your OS has been updated.',
                            time: '1hr',
                        },
                        {
                            id: 2,
                            image: '<span class="grid place-content-center w-9 h-9 rounded-full bg-info-light dark:bg-info text-info dark:text-info-light"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg></span>',
                            title: 'Did you know?',
                            message: 'You can switch between artboards.',
                            time: '2hr',
                        },
                        {
                            id: 3,
                            image: '<span class="grid place-content-center w-9 h-9 rounded-full bg-danger-light dark:bg-danger text-danger dark:text-danger-light"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></span>',
                            title: 'Something went wrong!',
                            message: 'Send Reposrt',
                            time: '2days',
                        },
                        {
                            id: 4,
                            image: '<span class="grid place-content-center w-9 h-9 rounded-full bg-warning-light dark:bg-warning text-warning dark:text-warning-light"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">    <circle cx="12" cy="12" r="10"></circle>    <line x1="12" y1="8" x2="12" y2="12"></line>    <line x1="12" y1="16" x2="12.01" y2="16"></line></svg></span>',
                            title: 'Warning',
                            message: 'Your password strength is low.',
                            time: '5days',
                        },
                    ],

                    languages: [
                        {
                            id: 1,
                            key: 'Chinese',
                            value: 'zh',
                        },
                        {
                            id: 2,
                            key: 'Danish',
                            value: 'da',
                        },
                        {
                            id: 3,
                            key: 'English',
                            value: 'en',
                        },
                        {
                            id: 4,
                            key: 'French',
                            value: 'fr',
                        },
                        {
                            id: 5,
                            key: 'German',
                            value: 'de',
                        },
                        {
                            id: 6,
                            key: 'Greek',
                            value: 'el',
                        },
                        {
                            id: 7,
                            key: 'Hungarian',
                            value: 'hu',
                        },
                        {
                            id: 8,
                            key: 'Italian',
                            value: 'it',
                        },
                        {
                            id: 9,
                            key: 'Japanese',
                            value: 'ja',
                        },
                        {
                            id: 10,
                            key: 'Polish',
                            value: 'pl',
                        },
                        {
                            id: 11,
                            key: 'Portuguese',
                            value: 'pt',
                        },
                        {
                            id: 12,
                            key: 'Russian',
                            value: 'ru',
                        },
                        {
                            id: 13,
                            key: 'Spanish',
                            value: 'es',
                        },
                        {
                            id: 14,
                            key: 'Swedish',
                            value: 'sv',
                        },
                        {
                            id: 15,
                            key: 'Turkish',
                            value: 'tr',
                        },
                        {
                            id: 16,
                            key: 'Arabic',
                            value: 'ae',
                        },
                    ],

                    removeNotification(value) {
                        this.notifications = this.notifications.filter((d) => d.id !== value);
                    },

                    removeMessage(value) {
                        this.messages = this.messages.filter((d) => d.id !== value);
                    },
                }));

                // content section
                // total-visit
                Alpine.data('analytics', () => ({
                    init() {
                        isDark = this.$store.app.theme === 'dark' || this.$store.app.isDarkMode ? true : false;
                        isRtl = this.$store.app.rtlClass === 'rtl' ? true : false;

                        const totalVisit = null;
                        const paidVisit = null;
                        const uniqueVisitorSeries = null;
                        const followers = null;
                        const referral = null;
                        const engagement = null;

                        // statistics
                        setTimeout(() => {
                            this.totalVisit = new ApexCharts(this.$refs.totalVisit, this.totalVisitOptions);
                            this.totalVisit.render();

                            this.paidVisit = new ApexCharts(this.$refs.paidVisit, this.paidVisitOptions);
                            this.paidVisit.render();

                            // unique visitors
                            this.uniqueVisitorSeries = new ApexCharts(this.$refs.uniqueVisitorSeries, this.uniqueVisitorSeriesOptions);
                            this.$refs.uniqueVisitorSeries.innerHTML = '';
                            this.uniqueVisitorSeries.render();

                            // followers
                            this.followers = new ApexCharts(this.$refs.followers, this.followersOptions);
                            this.followers.render();

                            // referral
                            this.referral = new ApexCharts(this.$refs.referral, this.referralOptions);
                            this.referral.render();

                            // engagement
                            this.engagement = new ApexCharts(this.$refs.engagement, this.engagementOptions);
                            this.engagement.render();
                        }, 300);

                        this.$watch('$store.app.theme', () => {
                            isDark = this.$store.app.theme === 'dark' || this.$store.app.isDarkMode ? true : false;
                            this.totalVisit.updateOptions(this.totalVisitOptions);
                            this.paidVisit.updateOptions(this.paidVisitOptions);
                            this.uniqueVisitorSeries.updateOptions(this.uniqueVisitorSeriesOptions);
                            this.followers.updateOptions(this.followersOptions);
                            this.referral.updateOptions(this.referralOptions);
                            this.engagement.updateOptions(this.engagementOptions);
                        });

                        this.$watch('$store.app.rtlClass', () => {
                            isRtl = this.$store.app.rtlClass === 'rtl' ? true : false;
                            this.uniqueVisitorSeries.updateOptions(this.uniqueVisitorSeriesOptions);
                        });
                    },

                    // statistics
                    get totalVisitOptions() {
                        return {
                            series: [
                                {
                                    data: [21, 9, 36, 12, 44, 25, 59, 41, 66, 25],
                                },
                            ],
                            chart: {
                                height: 58,
                                type: 'line',
                                fontFamily: 'Nunito, sans-serif',
                                sparkline: {
                                    enabled: true,
                                },
                                dropShadow: {
                                    enabled: true,
                                    blur: 3,
                                    color: '#009688',
                                    opacity: 0.4,
                                },
                            },
                            stroke: {
                                curve: 'smooth',
                                width: 2,
                            },
                            colors: ['#009688'],
                            grid: {
                                padding: {
                                    top: 5,
                                    bottom: 5,
                                    left: 5,
                                    right: 5,
                                },
                            },
                            tooltip: {
                                x: {
                                    show: false,
                                },
                                y: {
                                    title: {
                                        formatter: (formatter = () => {
                                            return '';
                                        }),
                                    },
                                },
                            },
                        };
                    },

                    //paid visit
                    get paidVisitOptions() {
                        return {
                            series: [
                                {
                                    data: [22, 19, 30, 47, 32, 44, 34, 55, 41, 69],
                                },
                            ],
                            chart: {
                                height: 58,
                                type: 'line',
                                fontFamily: 'Nunito, sans-serif',
                                sparkline: {
                                    enabled: true,
                                },
                                dropShadow: {
                                    enabled: true,
                                    blur: 3,
                                    color: '#e2a03f',
                                    opacity: 0.4,
                                },
                            },
                            stroke: {
                                curve: 'smooth',
                                width: 2,
                            },
                            colors: ['#e2a03f'],
                            grid: {
                                padding: {
                                    top: 5,
                                    bottom: 5,
                                    left: 5,
                                    right: 5,
                                },
                            },
                            tooltip: {
                                x: {
                                    show: false,
                                },
                                y: {
                                    title: {
                                        formatter: (formatter = () => {
                                            return '';
                                        }),
                                    },
                                },
                            },
                        };
                    },

                    // unique visitors
                    get uniqueVisitorSeriesOptions() {
                        return {
                            series: [
                                {
                                    name: 'Direct',
                                    data: [58, 44, 55, 57, 56, 61, 58, 63, 60, 66, 56, 63],
                                },
                                {
                                    name: 'Organic',
                                    data: [91, 76, 85, 101, 98, 87, 105, 91, 114, 94, 66, 70],
                                },
                            ],
                            chart: {
                                height: 360,
                                type: 'bar',
                                fontFamily: 'Nunito, sans-serif',
                                toolbar: {
                                    show: false,
                                },
                            },
                            dataLabels: {
                                enabled: false,
                            },
                            stroke: {
                                width: 2,
                                colors: ['transparent'],
                            },
                            colors: ['#5c1ac3', '#ffbb44'],
                            dropShadow: {
                                enabled: true,
                                blur: 3,
                                color: '#515365',
                                opacity: 0.4,
                            },
                            plotOptions: {
                                bar: {
                                    horizontal: false,
                                    columnWidth: '55%',
                                    borderRadius: 10,
                                },
                            },
                            legend: {
                                position: 'bottom',
                                horizontalAlign: 'center',
                                fontSize: '14px',
                                itemMargin: {
                                    horizontal: 8,
                                    vertical: 8,
                                },
                            },
                            grid: {
                                borderColor: isDark ? '#191e3a' : '#e0e6ed',
                                padding: {
                                    left: 20,
                                    right: 20,
                                },
                            },
                            xaxis: {
                                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                                axisBorder: {
                                    show: true,
                                    color: isDark ? '#3b3f5c' : '#e0e6ed',
                                },
                            },
                            yaxis: {
                                tickAmount: 6,
                                opposite: isRtl ? true : false,
                                labels: {
                                    offsetX: isRtl ? -10 : 0,
                                },
                            },
                            fill: {
                                type: 'gradient',
                                gradient: {
                                    shade: isDark ? 'dark' : 'light',
                                    type: 'vertical',
                                    shadeIntensity: 0.3,
                                    inverseColors: false,
                                    opacityFrom: 1,
                                    opacityTo: 0.8,
                                    stops: [0, 100],
                                },
                            },
                            tooltip: {
                                marker: {
                                    show: true,
                                },
                                y: {
                                    formatter: (val) => {
                                        return val;
                                    },
                                },
                            },
                        };
                    },

                    // followers
                    get followersOptions() {
                        return {
                            series: [
                                {
                                    data: [38, 60, 38, 52, 36, 40, 28],
                                },
                            ],
                            chart: {
                                height: 160,
                                type: 'area',
                                fontFamily: 'Nunito, sans-serif',
                                sparkline: {
                                    enabled: true,
                                },
                            },
                            stroke: {
                                curve: 'smooth',
                                width: 2,
                            },
                            colors: ['#4361ee'],
                            grid: {
                                padding: {
                                    top: 5,
                                },
                            },
                            yaxis: {
                                show: false,
                            },
                            tooltip: {
                                x: {
                                    show: false,
                                },
                                y: {
                                    title: {
                                        formatter: (formatter = () => {
                                            return '';
                                        }),
                                    },
                                },
                            },

                            if(isDark) {
                                option['fill'] = {
                                    type: 'gradient',
                                    gradient: {
                                        type: 'vertical',
                                        shadeIntensity: 1,
                                        inverseColors: !1,
                                        opacityFrom: 0.3,
                                        opacityTo: 0.05,
                                        stops: [100, 100],
                                    },
                                };
                            },
                        };
                    },

                    // referral
                    get referralOptions() {
                        return {
                            series: [
                                {
                                    data: [60, 28, 52, 38, 40, 36, 38],
                                },
                            ],
                            chart: {
                                height: 160,
                                type: 'area',
                                fontFamily: 'Nunito, sans-serif',
                                sparkline: {
                                    enabled: true,
                                },
                            },
                            stroke: {
                                curve: 'smooth',
                                width: 2,
                            },
                            colors: ['#e7515a'],
                            grid: {
                                padding: {
                                    top: 5,
                                },
                            },
                            yaxis: {
                                show: false,
                            },
                            tooltip: {
                                x: {
                                    show: false,
                                },
                                y: {
                                    title: {
                                        formatter: (formatter = () => {
                                            return '';
                                        }),
                                    },
                                },
                            },

                            if(isDark) {
                                option['fill'] = {
                                    type: 'gradient',
                                    gradient: {
                                        type: 'vertical',
                                        shadeIntensity: 1,
                                        inverseColors: !1,
                                        opacityFrom: 0.3,
                                        opacityTo: 0.05,
                                        stops: [100, 100],
                                    },
                                };
                            },
                        };
                    },

                    // engagement
                    get engagementOptions() {
                        return {
                            series: [
                                {
                                    name: 'Sales',
                                    data: [28, 50, 36, 60, 38, 52, 38],
                                },
                            ],
                            chart: {
                                height: 160,
                                type: 'area',
                                fontFamily: 'Nunito, sans-serif',
                                sparkline: {
                                    enabled: true,
                                },
                            },
                            stroke: {
                                curve: 'smooth',
                                width: 2,
                            },
                            colors: ['#1abc9c'],
                            grid: {
                                padding: {
                                    top: 5,
                                },
                            },
                            yaxis: {
                                show: false,
                            },
                            tooltip: {
                                x: {
                                    show: false,
                                },
                                y: {
                                    title: {
                                        formatter: (formatter = () => {
                                            return '';
                                        }),
                                    },
                                },
                            },

                            if(isDark) {
                                option['fill'] = {
                                    type: 'gradient',
                                    gradient: {
                                        type: 'vertical',
                                        shadeIntensity: 1,
                                        inverseColors: !1,
                                        opacityFrom: 0.3,
                                        opacityTo: 0.05,
                                        stops: [100, 100],
                                    },
                                };
                            },
                        };
                    },
                }));
                //scrumboard
                Alpine.data('scrumboard', () => ({
                    params: {
                        id: null,
                        title: '',
                    },
                    paramsTask: {
                        projectId: null,
                        id: null,
                        title: '',
                        description: '',
                        tags: '',
                    },
                    selectedTask: null,
                    isAddProjectModal: false,
                    isAddTaskModal: false,
                    isDeleteModal: false,
                    projectList: [
                        {
                            id: 1,
                            title: 'In Progress',
                            tasks: [
                                {
                                    projectId: 1,
                                    id: 1,
                                    title: 'Creating a new Portfolio on Dribble',
                                    description: 'Lorem ipsum dolor sit amet, consectetur adipisicing elit.',
                                    image: true,
                                    date: ' 08 Aug, 2020',
                                    tags: ['designing'],
                                },
                                {
                                    projectId: 1,
                                    id: 2,
                                    title: 'Singapore Team Meet',
                                    description: 'Lorem ipsum dolor sit amet, consectetur adipisicing elit.',
                                    date: ' 09 Aug, 2020',
                                    tags: ['meeting'],
                                },
                            ],
                        },
                        {
                            id: 2,
                            title: 'Pending',
                            tasks: [
                                {
                                    projectId: 2,
                                    id: 1,
                                    title: 'Plan a trip to another country',
                                    description: '',
                                    date: ' 10 Sep, 2020',
                                },
                            ],
                        },
                        {
                            id: 3,
                            title: 'Complete',
                            tasks: [
                                {
                                    projectId: 3,
                                    id: 1,
                                    title: 'Dinner with Kelly Young',
                                    description: '',
                                    date: ' 08 Aug, 2020',
                                },
                                {
                                    projectId: 3,
                                    id: 2,
                                    title: 'Launch New SEO Wordpress Theme ',
                                    description:
                                        'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                                    date: ' 09 Aug, 2020',
                                },
                            ],
                        },
                        {
                            id: 4,
                            title: 'Working',
                            tasks: [],
                        },
                    ],

                    init() {
                        this.initializeSortable();
                        // this.$watch('projectList', value => {
                        //     this.initializeSortable();
                        // });
                    },

                    initializeSortable() {
                        setTimeout(() => {
                            //sortable js
                            const sortable = document.querySelectorAll('.sortable-list');
                            for (let i = 0; i < sortable.length; i++) {
                                Sortable.create(sortable[i], {
                                    animation: 200,
                                    group: 'name',
                                    ghostClass: 'sortable-ghost',
                                    dragClass: 'sortable-drag',
                                });
                            }
                        });
                    },

                    addEditProject(project) {
                        setTimeout(() => {
                            this.params = {
                                id: null,
                                title: '',
                            };
                            if (project) {
                                this.params = JSON.parse(JSON.stringify(project));
                            }

                            this.isAddProjectModal = true;
                        });
                    },

                    saveProject() {
                        if (!this.params.title) {
                            this.showMessage('Title is required.', 'error');
                            return false;
                        }

                        if (this.params.id) {
                            //update project
                            const project = this.projectList.find((d) => d.id === this.params.id);
                            project.title = this.params.title;
                        } else {
                            //add project
                            const lastId = this.projectList.length
                                ? this.projectList.reduce((max, obj) => (obj.id > max ? obj.id : max), this.projectList[0].id)
                                : 0;

                            const project = {
                                id: lastId + 1,
                                title: this.params.title,
                                tasks: [],
                            };
                            this.projectList.push(project);
                        }

                        this.initializeSortable();
                        this.showMessage('Project has been saved successfully.');
                        this.isAddProjectModal = false;
                    },

                    deleteProject(project) {
                        this.projectList = this.projectList.filter((d) => d.id != project.id);
                        this.showMessage('Project has been deleted successfully.');
                    },

                    clearProjects(project) {
                        project.tasks = [];
                    },

                    // task
                    addEditTask(projectId, task) {
                        this.paramsTask = {
                            projectId: null,
                            id: null,
                            title: '',
                            description: '',
                            tags: '',
                        };
                        if (task) {
                            this.paramsTask = JSON.parse(JSON.stringify(task));
                            this.paramsTask.tags = this.paramsTask.tags ? this.paramsTask.tags.toString() : '';
                        }
                        this.paramsTask.projectId = projectId;
                        this.isAddTaskModal = true;
                    },

                    saveTask() {
                        if (!this.paramsTask.title) {
                            this.showMessage('Title is required.', 'error');
                            return false;
                        }

                        const project = this.projectList.find((d) => d.id === this.paramsTask.projectId);
                        if (this.paramsTask.id) {
                            //update task
                            const task = project.tasks.find((d) => d.id === this.paramsTask.id);
                            task.title = this.paramsTask.title;
                            task.description = this.paramsTask.description;
                            task.tags = this.paramsTask.tags?.length > 0 ? this.paramsTask.tags.split(',') : [];
                        } else {
                            //add task
                            let maxid = 0;
                            if (project.tasks?.length) {
                                maxid = project.tasks.reduce((max, obj) => (obj.id > max ? obj.id : max), project.tasks[0].id);
                            }

                            const today = new Date();
                            const dd = String(today.getDate()).padStart(2, '0');
                            const mm = String(today.getMonth()); //January is 0!
                            const yyyy = today.getFullYear();
                            const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

                            const task = {
                                projectId: this.paramsTask.projectId,
                                id: maxid + 1,
                                title: this.paramsTask.title,
                                description: this.paramsTask.description,
                                date: dd + ' ' + monthNames[mm] + ', ' + yyyy,
                                tags: this.paramsTask.tags?.length > 0 ? this.paramsTask.tags.split(',') : [],
                            };

                            project.tasks.push(task);
                        }

                        this.showMessage('Task has been saved successfully.');
                        this.isAddTaskModal = false;
                    },

                    deleteConfirmModal(projectId, task) {
                        this.selectedTask = task;
                        setTimeout(() => {
                            this.isDeleteModal = true;
                        }, 10);
                    },

                    deleteTask() {
                        let project = this.projectList.find((d) => d.id === this.selectedTask.projectId);
                        project.tasks = project.tasks.filter((d) => d.id != this.selectedTask.id);

                        this.showMessage('Task has been deleted successfully.');
                        this.isDeleteModal = false;
                    },

                    showMessage(msg = '', type = 'success') {
                        const toast = window.Swal.mixin({
                            toast: true,
                            position: 'top',
                            showConfirmButton: false,
                            timer: 3000,
                        });
                        toast.fire({
                            icon: type,
                            title: msg,
                            padding: '10px 20px',
                        });
                    },
                }));
            });
            
            
        </script>
    </body>
</html>
