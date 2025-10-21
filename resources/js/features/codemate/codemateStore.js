import { defineStore } from 'pinia';

export const useCodemateStore = defineStore('codemate', {
    state: () => ({
        topLevelFolders: [],
        selectedFolderId: null,
        selectedFile: null,
        watchedFiles: [],
        uninitializedFiles: [],
        showWatchedTree: false,
        showUninitializedTree: false,
    }),
    
    getters: {
        selectedFolder: (state) => {
            return state.topLevelFolders.find(folder => folder.id === state.selectedFolderId) || null;
        },
        
        hasTopLevelFolders: (state) => {
            return state.topLevelFolders.length > 0;
        }
    },
    
    actions: {
        setTopLevelFolders(folders) {
            this.topLevelFolders = folders;
        },
        
        setSelectedFolderId(folderId) {
            this.selectedFolderId = folderId;
            // Persist to localStorage
            if (folderId) {
                localStorage.setItem('codemate_selected_folder_id', folderId.toString());
            } else {
                localStorage.removeItem('codemate_selected_folder_id');
            }
        },
        
        setSelectedFile(file) {
            this.selectedFile = file;
        },
        
        loadSelectedFolderFromStorage() {
            const storedId = localStorage.getItem('codemate_selected_folder_id');
            if (storedId) {
                this.selectedFolderId = parseInt(storedId);
            }
        },
        
        async fetchWatchedFiles() {
            try {
                const response = await fetch('/codemate/watched-files');
                const watchedFiles = await response.json();
                this.watchedFiles = watchedFiles;
            } catch (error) {
                console.error('Failed to fetch watched files:', error);
                this.watchedFiles = [];
            }
        },
        
        async fetchUninitializedFiles() {
            if (!this.selectedFolderId) return;
            
            try {
                const response = await fetch('/codemate/mount', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ folder_id: this.selectedFolderId })
                });
                const uninitializedFiles = await response.json();
                this.uninitializedFiles = uninitializedFiles;
            } catch (error) {
                console.error('Failed to fetch uninitialized files:', error);
                this.uninitializedFiles = [];
            }
        },
        
        async toggleWatchedTree() {
            this.showWatchedTree = !this.showWatchedTree;
            
            // Fetch watched files when showing the tree
            if (this.showWatchedTree) {
                await this.fetchWatchedFiles();
            }
        },
        
        async toggleUninitializedTree() {
            this.showUninitializedTree = !this.showUninitializedTree;
            
            // Fetch uninitialized files when showing the tree
            if (this.showUninitializedTree) {
                await this.fetchUninitializedFiles();
            }
        },
        
        initializeStore(serverData) {
            this.setTopLevelFolders(serverData.topLevelFolders || []);
            this.loadSelectedFolderFromStorage();
            
            // If stored folder ID doesn't exist in current folders, clear it
            if (this.selectedFolderId && !this.selectedFolder) {
                this.setSelectedFolderId(null);
            }
        }
    }
});