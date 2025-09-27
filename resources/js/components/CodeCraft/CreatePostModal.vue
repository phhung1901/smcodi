<script setup lang="ts">
import { ref, nextTick, watch } from 'vue';

// Props
interface Props {
  isOpen: boolean;
}

const props = defineProps<Props>();

// Emits
const emit = defineEmits<{
  close: [];
  submit: [data: {
    title: string;
    description: string;
    badCode: string;
    goodCode: string;
    badCodeLang: string;
    goodCodeLang: string;
  }];
}>();

// Reactive data
const title = ref('');
const description = ref('');
const badCode = ref('');
const goodCode = ref('');
const badCodeLang = ref('javascript');
const goodCodeLang = ref('javascript');

// Methods
const closeModal = () => {
  emit('close');
};

const updateLineNumbers = (editorId: string) => {
  nextTick(() => {
    const editor = document.getElementById(editorId) as HTMLTextAreaElement;
    const lineNumbersEl = document.getElementById(editorId + 'Lines');

    if (editor && lineNumbersEl) {
      const lines = editor.value.split('\n');
      const lineNumbers = lines.map((_, index) => index + 1).join('\n');
      lineNumbersEl.textContent = lineNumbers;
    }
  });
};

const formatCode = (editorId: string) => {
  const editor = document.getElementById(editorId) as HTMLTextAreaElement;
  if (!editor) return;

  let code = editor.value;

  // Basic formatting
  code = code.replace(/\s+/g, ' ');
  code = code.replace(/;\s*/g, ';\n');
  code = code.replace(/{\s*/g, ' {\n');
  code = code.replace(/}\s*/g, '\n}\n');

  // Indentation
  let lines = code.split('\n');
  let indentLevel = 0;
  let formattedLines: string[] = [];

  lines.forEach(line => {
    line = line.trim();
    if (line.includes('}')) indentLevel--;
    formattedLines.push('    '.repeat(Math.max(0, indentLevel)) + line);
    if (line.includes('{')) indentLevel++;
  });

  editor.value = formattedLines.join('\n').trim();
  updateLineNumbers(editorId);
};

const copyCode = async (editorId: string) => {
  const editor = document.getElementById(editorId) as HTMLTextAreaElement;
  if (!editor) return;

  try {
    await navigator.clipboard.writeText(editor.value);
    // Could add a toast notification here
  } catch (err) {
    console.error('Failed to copy code:', err);
  }
};

const postContent = () => {
  if (!title.value || !description.value || !badCode.value || !goodCode.value) {
    alert('Vui lòng điền đầy đủ thông tin!');
    return;
  }

  emit('submit', {
    title: title.value,
    description: description.value,
    badCode: badCode.value,
    goodCode: goodCode.value,
    badCodeLang: badCodeLang.value,
    goodCodeLang: goodCodeLang.value
  });

  // Reset form
  title.value = '';
  description.value = '';
  badCode.value = '';
  goodCode.value = '';
  badCodeLang.value = 'javascript';
  goodCodeLang.value = 'javascript';
};

const handleOverlayClick = (e: Event) => {
  if (e.target === e.currentTarget) {
    closeModal();
  }
};

const handleTabKey = (e: KeyboardEvent) => {
  if (e.key === 'Tab') {
    e.preventDefault();
    const target = e.target as HTMLTextAreaElement;
    const start = target.selectionStart;
    const end = target.selectionEnd;
    target.value = target.value.substring(0, start) + '    ' + target.value.substring(end);
    target.selectionStart = target.selectionEnd = start + 4;
  }
};

// Watch for modal open to add sample code
watch(() => props.isOpen, (newValue) => {
  if (newValue && !badCode.value && !goodCode.value) {
    // Add sample code for demonstration
    badCode.value = `// Vòng lặp lồng nhau - O(n²)
const result = [];
for (let i = 0; i < array1.length; i++) {
    for (let j = 0; j < array2.length; j++) {
        if (array1[i].id === array2[j].id) {
            result.push({
                ...array1[i],
                ...array2[j]
            });
        }
    }
}`;

    goodCode.value = `// Sử dụng Map - O(n)
const map = new Map(array2.map(item => [item.id, item]));
const result = array1
    .filter(item => map.has(item.id))
    .map(item => ({
        ...item,
        ...map.get(item.id)
    }));`;

    nextTick(() => {
      updateLineNumbers('badCode');
      updateLineNumbers('goodCode');
    });
  }
});
</script>

<template>
  <!-- Modal Overlay -->
  <div
    v-if="isOpen"
    class="modal-overlay"
    @click="handleOverlayClick"
  >
    <div class="modal-container">
      <!-- Header -->
      <div class="modal-header">
        <h2>Đăng bài</h2>
        <button class="close-btn" @click="closeModal">✕</button>
      </div>

      <!-- Body -->
      <div class="modal-body">
        <!-- Title -->
        <div class="form-group">
          <label for="title">Tiêu đề</label>
          <input
            id="title"
            v-model="title"
            type="text"
            placeholder="VD: Tối ưu vòng lặp trong JavaScript"
          >
        </div>

        <!-- Description -->
        <div class="form-group">
          <label for="description">Mô tả ngắn</label>
          <textarea
            id="description"
            v-model="description"
            rows="2"
            placeholder="VD: Cách tránh vòng lặp lồng nhau không cần thiết khi xử lý mảng"
          ></textarea>
        </div>

        <!-- Code Comparison Section -->
        <div class="code-comparison">
          <!-- Bad Code Section -->
          <div class="code-section bad-code">
            <div class="code-header">
              <div class="code-label">
                <span class="status-circle red"></span>
              </div>
              <div class="code-actions">
                <select v-model="badCodeLang" class="language-select">
                  <option value="javascript">JavaScript</option>
                  <option value="python">Python</option>
                  <option value="java">Java</option>
                  <option value="cpp">C++</option>
                  <option value="html">HTML</option>
                  <option value="css">CSS</option>
                </select>
                <button class="code-btn" @click="formatCode('badCode')">Format</button>
                <button class="code-btn copy-btn" @click="copyCode('badCode')">
                  <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1v-1z"/>
                    <path d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5h3zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3z"/>
                  </svg>
                </button>
              </div>
            </div>
            <div class="code-editor-wrapper">
              <div class="line-numbers" id="badCodeLines"></div>
              <textarea
                id="badCode"
                v-model="badCode"
                class="code-editor"
                placeholder="// Paste code chưa tối ưu ở đây..."
                @input="updateLineNumbers('badCode')"
                @keydown="handleTabKey"
              ></textarea>
            </div>
          </div>

          <!-- Good Code Section -->
          <div class="code-section good-code">
            <div class="code-header">
              <div class="code-label">
                <span class="status-circle green"></span>
              </div>
              <div class="code-actions">
                <select v-model="goodCodeLang" class="language-select">
                  <option value="javascript">JavaScript</option>
                  <option value="python">Python</option>
                  <option value="java">Java</option>
                  <option value="cpp">C++</option>
                  <option value="html">HTML</option>
                  <option value="css">CSS</option>
                </select>
                <button class="code-btn" @click="formatCode('goodCode')">Format</button>
                <button class="code-btn copy-btn" @click="copyCode('goodCode')">
                  <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1v-1z"/>
                    <path d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5h3zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3z"/>
                  </svg>
                </button>
              </div>
            </div>
            <div class="code-editor-wrapper">
              <div class="line-numbers" id="goodCodeLines"></div>
              <textarea
                id="goodCode"
                v-model="goodCode"
                class="code-editor"
                placeholder="// Paste code đã tối ưu ở đây..."
                @input="updateLineNumbers('goodCode')"
                @keydown="handleTabKey"
              ></textarea>
            </div>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <div class="modal-footer">
        <button class="btn btn-cancel" @click="closeModal">Hủy bỏ</button>
        <button class="btn btn-post" @click="postContent">Đăng bài</button>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Modal Overlay */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.7);
  backdrop-filter: blur(5px);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
  animation: fadeIn 0.3s ease;
}

/* Modal Container */
.modal-container {
  background: linear-gradient(145deg, rgba(255,255,255,0.95), rgba(240,240,240,0.95));
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255,255,255,0.3);
  border-radius: 16px;
  width: 90%;
  max-width: 900px;
  max-height: 90vh;
  overflow-y: auto;
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
  animation: slideUp 0.3s ease;
}

/* Modal Header */
.modal-header {
  background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
  color: white;
  padding: 20px 24px;
  border-radius: 16px 16px 0 0;
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 1px solid rgba(255,255,255,0.1);
}

.modal-header h2 {
  font-size: 20px;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 10px;
}

.close-btn {
  background: transparent;
  border: none;
  color: #999;
  font-size: 24px;
  cursor: pointer;
  transition: all 0.3s ease;
  width: 36px;
  height: 36px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.close-btn:hover {
  background: rgba(255,255,255,0.1);
  color: white;
  transform: rotate(90deg);
}

/* Modal Body */
.modal-body {
  padding: 24px;
}

/* Form Groups */
.form-group {
  margin-bottom: 20px;
}

.form-group label {
  display: block;
  margin-bottom: 8px;
  color: #1a1a1a;
  font-weight: 500;
  font-size: 14px;
}

.form-group input,
.form-group textarea {
  width: 100%;
  padding: 12px 16px;
  border: 2px solid #e0e0e0;
  border-radius: 8px;
  font-size: 14px;
  transition: all 0.3s ease;
  background: rgba(255,255,255,0.8);
}

.form-group input:focus,
.form-group textarea:focus {
  outline: none;
  border-color: #1a1a1a;
  background: white;
  box-shadow: 0 0 0 3px rgba(26, 26, 26, 0.1);
}

/* Code Comparison */
.code-comparison {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 16px;
  margin-top: 20px;
}

.code-section {
  border: 2px solid #e0e0e0;
  border-radius: 12px;
  overflow: hidden;
  background: #1a1a1a;
}

.code-section.bad-code {
  border-color: rgba(255, 107, 107, 0.3);
}

.code-section.good-code {
  border-color: rgba(34, 197, 94, 0.3);
}

.code-header {
  background: #2d2d2d;
  padding: 12px 16px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 1px solid rgba(255,255,255,0.1);
}

.code-label {
  display: flex;
  align-items: center;
  gap: 8px;
  color: white;
  font-size: 12px;
  font-weight: 500;
}

.status-circle {
  width: 8px;
  height: 8px;
  border-radius: 50%;
}

.status-circle.red {
  background: #ff6b6b;
}

.status-circle.green {
  background: #22c55e;
}

.code-actions {
  display: flex;
  gap: 8px;
  align-items: center;
}

.code-btn {
  background: rgba(255,255,255,0.1);
  border: 1px solid rgba(255,255,255,0.2);
  color: white;
  padding: 4px 8px;
  border-radius: 4px;
  font-size: 12px;
  cursor: pointer;
  transition: all 0.3s ease;
}

.code-btn:hover {
  background: rgba(255,255,255,0.2);
}

.language-select {
  background: rgba(255,255,255,0.1);
  border: 1px solid rgba(255,255,255,0.2);
  color: white;
  padding: 4px 8px;
  border-radius: 4px;
  font-size: 12px;
  cursor: pointer;
  outline: none;
}

.language-select option {
  background: #2d2d2d;
}

.code-editor-wrapper {
  display: flex;
  background: #1a1a1a;
  min-height: 200px;
}

.line-numbers {
  background: #2d2d2d;
  color: #666;
  padding: 16px 8px;
  font-family: 'Fira Code', 'Monaco', 'Consolas', monospace;
  font-size: 13px;
  line-height: 1.5;
  text-align: right;
  user-select: none;
  border-right: 1px solid rgba(255,255,255,0.1);
  white-space: pre;
}

.code-editor {
  flex: 1;
  background: transparent;
  border: none;
  color: #e0e0e0;
  padding: 16px;
  font-family: 'Fira Code', 'Monaco', 'Consolas', monospace;
  font-size: 13px;
  line-height: 1.5;
  resize: none;
  outline: none;
  white-space: pre;
  overflow-wrap: normal;
  overflow-x: auto;
}

/* Modal Footer */
.modal-footer {
  background: linear-gradient(135deg, rgba(245,245,245,0.95), rgba(255,255,255,0.95));
  padding: 20px 24px;
  border-top: 1px solid #e0e0e0;
  display: flex;
  justify-content: flex-end;
  gap: 12px;
  border-radius: 0 0 16px 16px;
}

.btn {
  padding: 10px 20px;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.3s ease;
  border: none;
  display: flex;
  align-items: center;
  gap: 6px;
}

.btn-cancel {
  background: transparent;
  color: #ff6b6b;
  border: 2px solid rgba(255, 107, 107, 0.5);
}

.btn-cancel:hover {
  background: rgba(255, 107, 107, 0.1);
  border-color: #ff6b6b;
}

.btn-post {
  background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
  color: white;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.btn-post:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 12px -2px rgba(0, 0, 0, 0.2);
}

/* Animations */
@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

@keyframes slideUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Scrollbar Styling */
.modal-container::-webkit-scrollbar,
.code-editor::-webkit-scrollbar {
  width: 8px;
  height: 8px;
}

.modal-container::-webkit-scrollbar-track,
.code-editor::-webkit-scrollbar-track {
  background: rgba(255,255,255,0.1);
  border-radius: 4px;
}

.modal-container::-webkit-scrollbar-thumb,
.code-editor::-webkit-scrollbar-thumb {
  background: rgba(255,255,255,0.3);
  border-radius: 4px;
}

.modal-container::-webkit-scrollbar-thumb:hover,
.code-editor::-webkit-scrollbar-thumb:hover {
  background: rgba(255,255,255,0.5);
}

/* Responsive */
@media (max-width: 768px) {
  .code-comparison {
    grid-template-columns: 1fr;
  }

  .modal-container {
    width: 95%;
    margin: 10px;
  }
}
</style>
