/**
 * @author Lukáš
 * @version 1.0.0
 * @package Topazz
 */

import Projects from "./project/Projects.vue";
import ProjectList from "./project/ProjectList.vue";
import ProjectDetail from "./project/ProjectDetail.vue";

export const ProjectsRouting = {
    path: '/projects', component: Projects,
    meta: {
        breadcrumb: 'Projects',
        permissions: ['system.projects']
    },
    children: [
        {
            path: '', component: ProjectList, name: 'projects',
            meta: {
                breadcrumb: 'All'
            }
        }, {
            path: ':id', component: ProjectDetail, name: 'project', props: true,
            meta: {
                breadcrumb: 'Detail',
                permissions: ['project.all']
            }
        }
    ]
};