<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import CodeCraftLayout from '@/layouts/CodeCraftLayout.vue';
import Navigation from '@/components/CodeCraft/Navigation.vue';
import PostCreator from '@/components/CodeCraft/PostCreator.vue';
import SearchBar from '@/components/CodeCraft/SearchBar.vue';
import PostCard from '@/components/CodeCraft/PostCard.vue';

// Sample posts data
const samplePosts = [
    {
        id: '1',
        title: 'Tối ưu vòng lặp trong JavaScript',
        description: 'Cách tránh vòng lặp lồng nhau không cần thiết khi xử lý mảng',
        author: {
            name: 'Trần Hoàng',
            avatar: '',
            initials: 'TH'
        },
        timeAgo: '2 giờ trước',
        badCode: [
            { number: 1, content: '<span class="comment">// Tìm users có order</span>' },
            { number: 2, content: '<span class="keyword">let</span> <span class="variable">usersWithOrders</span> <span class="operator">=</span> <span class="punctuation">[];</span>' },
            { number: 3, content: '<span class="keyword">for</span> <span class="punctuation">(</span><span class="keyword">let</span> <span class="variable">i</span> <span class="operator">=</span> <span class="number">0</span><span class="punctuation">;</span> <span class="variable">i</span> <span class="operator">&lt;</span> <span class="variable">users</span><span class="punctuation">.</span><span class="property">length</span><span class="punctuation">;</span> <span class="variable">i</span><span class="operator">++</span><span class="punctuation">)</span> <span class="punctuation">{</span>' },
            { number: 4, content: '    <span class="keyword">for</span> <span class="punctuation">(</span><span class="keyword">let</span> <span class="variable">j</span> <span class="operator">=</span> <span class="number">0</span><span class="punctuation">;</span> <span class="variable">j</span> <span class="operator">&lt;</span> <span class="variable">orders</span><span class="punctuation">.</span><span class="property">length</span><span class="punctuation">;</span> <span class="variable">j</span><span class="operator">++</span><span class="punctuation">)</span> <span class="punctuation">{</span>' },
            { number: 5, content: '        <span class="keyword">if</span> <span class="punctuation">(</span><span class="variable">users</span><span class="punctuation">[</span><span class="variable">i</span><span class="punctuation">].</span><span class="property">id</span> <span class="operator">===</span> <span class="variable">orders</span><span class="punctuation">[</span><span class="variable">j</span><span class="punctuation">].</span><span class="property">userId</span><span class="punctuation">)</span> <span class="punctuation">{</span>' },
            { number: 6, content: '            <span class="variable">usersWithOrders</span><span class="punctuation">.</span><span class="method">push</span><span class="punctuation">(</span><span class="variable">users</span><span class="punctuation">[</span><span class="variable">i</span><span class="punctuation">]);</span>' },
            { number: 7, content: '            <span class="keyword">break</span><span class="punctuation">;</span>' },
            { number: 8, content: '        <span class="punctuation">}</span>' },
            { number: 9, content: '    <span class="punctuation">}</span>' },
            { number: 10, content: '<span class="punctuation">}</span>' }
        ],
        goodCode: [
            { number: 1, content: '<span class="comment">// Sử dụng Set để tối ưu performance</span>' },
            { number: 2, content: '<span class="keyword">const</span> <span class="variable">userIdsWithOrders</span> <span class="operator">=</span> <span class="keyword">new</span> <span class="class-name">Set</span><span class="punctuation">(</span>' },
            { number: 3, content: '    <span class="variable">orders</span><span class="punctuation">.</span><span class="method">map</span><span class="punctuation">(</span><span class="variable">order</span> <span class="operator">=&gt;</span> <span class="variable">order</span><span class="punctuation">.</span><span class="property">userId</span><span class="punctuation">)</span>' },
            { number: 4, content: '<span class="punctuation">);</span>' },
            { number: 5, content: '' },
            { number: 6, content: '<span class="keyword">const</span> <span class="variable">usersWithOrders</span> <span class="operator">=</span> <span class="variable">users</span><span class="punctuation">.</span><span class="method">filter</span><span class="punctuation">(</span><span class="variable">user</span> <span class="operator">=&gt;</span>' },
            { number: 7, content: '    <span class="variable">userIdsWithOrders</span><span class="punctuation">.</span><span class="method">has</span><span class="punctuation">(</span><span class="variable">user</span><span class="punctuation">.</span><span class="property">id</span><span class="punctuation">)</span>' },
            { number: 8, content: '<span class="punctuation">);</span>' }
        ],
        voteCount: 342,
        commentCount: 28,
        isVoted: true,
        isSaved: false,
        language: 'javascript'
    },
    {
        id: '2',
        title: 'React: Quản lý State hiệu quả',
        description: 'Tránh re-render không cần thiết khi update state trong React',
        author: {
            name: 'Lê Nam',
            avatar: '',
            initials: 'LN'
        },
        timeAgo: '5 giờ trước',
        badCode: [
            { number: 1, content: '<span class="comment">// Multiple setState gây re-render nhiều lần</span>' },
            { number: 2, content: '<span class="keyword">const</span> <span class="function">handleUpdate</span> <span class="operator">=</span> <span class="punctuation">()</span> <span class="operator">=&gt;</span> <span class="punctuation">{</span>' },
            { number: 3, content: '    <span class="method">setLoading</span><span class="punctuation">(</span><span class="keyword">true</span><span class="punctuation">);</span>' },
            { number: 4, content: '    <span class="method">setError</span><span class="punctuation">(</span><span class="keyword">null</span><span class="punctuation">);</span>' },
            { number: 5, content: '    <span class="method">setData</span><span class="punctuation">(</span><span class="keyword">null</span><span class="punctuation">);</span>' }
        ],
        goodCode: [
            { number: 1, content: '<span class="comment">// Sử dụng useReducer để batch updates</span>' },
            { number: 2, content: '<span class="keyword">const</span> <span class="punctuation">[</span><span class="variable">state</span><span class="punctuation">,</span> <span class="variable">dispatch</span><span class="punctuation">]</span> <span class="operator">=</span> <span class="method">useReducer</span><span class="punctuation">(</span><span class="variable">reducer</span><span class="punctuation">,</span> <span class="variable">initialState</span><span class="punctuation">);</span>' },
            { number: 3, content: '' },
            { number: 4, content: '<span class="keyword">const</span> <span class="function">handleUpdate</span> <span class="operator">=</span> <span class="keyword">async</span> <span class="punctuation">()</span> <span class="operator">=&gt;</span> <span class="punctuation">{</span>' },
            { number: 5, content: '    <span class="method">dispatch</span><span class="punctuation">({</span> <span class="property">type</span><span class="punctuation">:</span> <span class="string">\'FETCH_START\'</span> <span class="punctuation">});</span>' }
        ],
        voteCount: 156,
        commentCount: 14,
        isVoted: false,
        isSaved: false,
        language: 'javascript'
    },
    {
        id: '3',
        title: 'Python: Xử lý Exception đúng cách',
        description: 'Cách handle exception an toàn và rõ ràng trong Python',
        author: {
            name: 'Phạm Thảo',
            avatar: '',
            initials: 'PT'
        },
        timeAgo: '8 giờ trước',
        badCode: [
            { number: 1, content: '<span class="comment"># Catch tất cả exception - không nên</span>' },
            { number: 2, content: '<span class="keyword">try</span><span class="punctuation">:</span>' },
            { number: 3, content: '    <span class="variable">user</span> <span class="operator">=</span> <span class="method">get_user</span><span class="punctuation">(</span><span class="variable">user_id</span><span class="punctuation">)</span>' },
            { number: 4, content: '    <span class="method">process_user</span><span class="punctuation">(</span><span class="variable">user</span><span class="punctuation">)</span>' },
            { number: 5, content: '    <span class="method">save_to_db</span><span class="punctuation">(</span><span class="variable">user</span><span class="punctuation">)</span>' },
            { number: 6, content: '<span class="keyword">except</span><span class="punctuation">:</span>' },
            { number: 7, content: '    <span class="method">print</span><span class="punctuation">(</span><span class="string">"Something went wrong"</span><span class="punctuation">)</span>' },
            { number: 8, content: '    <span class="keyword">return</span> <span class="keyword">None</span>' }
        ],
        goodCode: [
            { number: 1, content: '<span class="comment"># Xử lý exception cụ thể</span>' },
            { number: 2, content: '<span class="keyword">try</span><span class="punctuation">:</span>' },
            { number: 3, content: '    <span class="variable">user</span> <span class="operator">=</span> <span class="method">get_user</span><span class="punctuation">(</span><span class="variable">user_id</span><span class="punctuation">)</span>' },
            { number: 4, content: '    <span class="method">process_user</span><span class="punctuation">(</span><span class="variable">user</span><span class="punctuation">)</span>' },
            { number: 5, content: '    <span class="method">save_to_db</span><span class="punctuation">(</span><span class="variable">user</span><span class="punctuation">)</span>' },
            { number: 6, content: '<span class="keyword">except</span> <span class="class-name">UserNotFound</span><span class="punctuation">:</span>' },
            { number: 7, content: '    <span class="variable">logger</span><span class="punctuation">.</span><span class="method">warning</span><span class="punctuation">(</span><span class="keyword">f</span><span class="string">"User {user_id} not found"</span><span class="punctuation">)</span>' },
            { number: 8, content: '    <span class="keyword">return</span> <span class="keyword">None</span>' },
            { number: 9, content: '<span class="keyword">except</span> <span class="class-name">DatabaseError</span> <span class="keyword">as</span> <span class="variable">e</span><span class="punctuation">:</span>' },
            { number: 10, content: '    <span class="variable">logger</span><span class="punctuation">.</span><span class="method">error</span><span class="punctuation">(</span><span class="keyword">f</span><span class="string">"Database error: {e}"</span><span class="punctuation">)</span>' },
            { number: 11, content: '    <span class="keyword">raise</span>' }
        ],
        voteCount: 89,
        commentCount: 7,
        isVoted: false,
        isSaved: true,
        language: 'python'
    },
    {
        id: '4',
        title: 'Laravel Route Authentication',
        description: 'Best practices cho middleware authentication trong Laravel',
        author: {
            name: 'Vũ Duy',
            avatar: '',
            initials: 'VD'
        },
        timeAgo: '1 ngày trước',
        badCode: [
            { number: 1, content: '<span class="punctuation">&lt;?</span><span class="keyword">php</span>' },
            { number: 2, content: '' },
            { number: 3, content: '<span class="class-name">Route</span><span class="punctuation">::</span><span class="method">get</span><span class="punctuation">(</span><span class="string">\'/dashboard\'</span><span class="punctuation">,</span> <span class="keyword">function</span> <span class="punctuation">()</span> <span class="punctuation">{</span>' },
            { number: 4, content: '    <span class="keyword">if</span> <span class="punctuation">(!</span><span class="method">auth</span><span class="punctuation">()-></span><span class="method">check</span><span class="punctuation">())</span> <span class="punctuation">{</span>' },
            { number: 5, content: '        <span class="keyword">return</span> <span class="method">redirect</span><span class="punctuation">(</span><span class="string">\'/login\'</span><span class="punctuation">);</span>' },
            { number: 6, content: '    <span class="punctuation">}</span>' },
            { number: 7, content: '    <span class="keyword">if</span> <span class="punctuation">(!</span><span class="method">auth</span><span class="punctuation">()-></span><span class="property">user</span><span class="punctuation">()-></span><span class="property">verified</span><span class="punctuation">)</span> <span class="punctuation">{</span>' },
            { number: 8, content: '        <span class="keyword">return</span> <span class="method">redirect</span><span class="punctuation">(</span><span class="string">\'/verify-email\'</span><span class="punctuation">);</span>' },
            { number: 9, content: '    <span class="punctuation">}</span>' },
            { number: 10, content: '    <span class="keyword">return</span> <span class="class-name">Inertia</span><span class="punctuation">::</span><span class="method">render</span><span class="punctuation">(</span><span class="string">\'Dashboard\'</span><span class="punctuation">);</span>' },
            { number: 11, content: '<span class="punctuation">});</span>' }
        ],
        goodCode: [
            { number: 1, content: '<span class="punctuation">&lt;?</span><span class="keyword">php</span>' },
            { number: 2, content: '' },
            { number: 3, content: '<span class="class-name">Route</span><span class="punctuation">::</span><span class="method">get</span><span class="punctuation">(</span><span class="string">\'/dashboard\'</span><span class="punctuation">,</span> <span class="keyword">function</span> <span class="punctuation">()</span> <span class="punctuation">{</span>' },
            { number: 4, content: '    <span class="keyword">return</span> <span class="class-name">Inertia</span><span class="punctuation">::</span><span class="method">render</span><span class="punctuation">(</span><span class="string">\'Dashboard\'</span><span class="punctuation">);</span>' },
            { number: 5, content: '<span class="punctuation">})-></span><span class="method">middleware</span><span class="punctuation">([</span><span class="string">\'auth\'</span><span class="punctuation">,</span> <span class="string">\'verified\'</span><span class="punctuation">])</span>' },
            { number: 6, content: '  <span class="punctuation">-></span><span class="method">name</span><span class="punctuation">(</span><span class="string">\'dashboard\'</span><span class="punctuation">);</span>' }
        ],
        voteCount: 234,
        commentCount: 42,
        isVoted: false,
        isSaved: false,
        language: 'php'
    },
    {
        id: '5',
        title: 'CSS: Flexbox vs Grid Layout',
        description: 'Khi nào nên sử dụng Flexbox và khi nào nên sử dụng Grid',
        author: {
            name: 'Nguyễn Minh',
            avatar: '',
            initials: 'NM'
        },
        timeAgo: '2 ngày trước',
        badCode: [
            { number: 1, content: '<span class="comment">/* Sử dụng float cho layout - cũ */</span>' },
            { number: 2, content: '<span class="punctuation">.</span><span class="class-name">container</span> <span class="punctuation">{</span>' },
            { number: 3, content: '  <span class="property">width</span><span class="punctuation">:</span> <span class="number">100%</span><span class="punctuation">;</span>' },
            { number: 4, content: '<span class="punctuation">}</span>' },
            { number: 5, content: '' },
            { number: 6, content: '<span class="punctuation">.</span><span class="class-name">sidebar</span> <span class="punctuation">{</span>' },
            { number: 7, content: '  <span class="property">float</span><span class="punctuation">:</span> <span class="keyword">left</span><span class="punctuation">;</span>' },
            { number: 8, content: '  <span class="property">width</span><span class="punctuation">:</span> <span class="number">25%</span><span class="punctuation">;</span>' },
            { number: 9, content: '<span class="punctuation">}</span>' },
            { number: 10, content: '' },
            { number: 11, content: '<span class="punctuation">.</span><span class="class-name">content</span> <span class="punctuation">{</span>' },
            { number: 12, content: '  <span class="property">float</span><span class="punctuation">:</span> <span class="keyword">right</span><span class="punctuation">;</span>' },
            { number: 13, content: '  <span class="property">width</span><span class="punctuation">:</span> <span class="number">75%</span><span class="punctuation">;</span>' },
            { number: 14, content: '<span class="punctuation">}</span>' }
        ],
        goodCode: [
            { number: 1, content: '<span class="comment">/* Sử dụng CSS Grid cho layout */</span>' },
            { number: 2, content: '<span class="punctuation">.</span><span class="class-name">container</span> <span class="punctuation">{</span>' },
            { number: 3, content: '  <span class="property">display</span><span class="punctuation">:</span> <span class="keyword">grid</span><span class="punctuation">;</span>' },
            { number: 4, content: '  <span class="property">grid-template-columns</span><span class="punctuation">:</span> <span class="number">1</span><span class="keyword">fr</span> <span class="number">3</span><span class="keyword">fr</span><span class="punctuation">;</span>' },
            { number: 5, content: '  <span class="property">gap</span><span class="punctuation">:</span> <span class="number">20px</span><span class="punctuation">;</span>' },
            { number: 6, content: '  <span class="property">min-height</span><span class="punctuation">:</span> <span class="number">100vh</span><span class="punctuation">;</span>' },
            { number: 7, content: '<span class="punctuation">}</span>' },
            { number: 8, content: '' },
            { number: 9, content: '<span class="punctuation">.</span><span class="class-name">sidebar</span> <span class="punctuation">{</span>' },
            { number: 10, content: '  <span class="property">grid-area</span><span class="punctuation">:</span> <span class="number">1</span> <span class="operator">/</span> <span class="number">1</span><span class="punctuation">;</span>' },
            { number: 11, content: '<span class="punctuation">}</span>' },
            { number: 12, content: '' },
            { number: 13, content: '<span class="punctuation">.</span><span class="class-name">content</span> <span class="punctuation">{</span>' },
            { number: 14, content: '  <span class="property">grid-area</span><span class="punctuation">:</span> <span class="number">1</span> <span class="operator">/</span> <span class="number">2</span><span class="punctuation">;</span>' },
            { number: 15, content: '<span class="punctuation">}</span>' }
        ],
        voteCount: 178,
        commentCount: 23,
        isVoted: true,
        isSaved: false,
        language: 'css'
    }
];

// Handle search
const handleSearch = (searchValue: string) => {
    console.log('Search:', searchValue);
};
</script>

<template>
    <Head title="SMCodi - Learn from Better Code" />

    <!-- Navigation nằm ngoài layout -->
    <Navigation active-tab="home" />

    <CodeCraftLayout>
        <!-- Post Creator -->
        <PostCreator />

        <!-- Search Bar -->
        <SearchBar @search="handleSearch" />

        <!-- Posts List -->
        <div class="space-y-4">
            <PostCard
                v-for="post in samplePosts"
                :key="post.id"
                :post="post"
            />
        </div>
    </CodeCraftLayout>
</template>

<style scoped>
/* Page-specific styles if needed */
</style>
