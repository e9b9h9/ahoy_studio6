import axios from 'axios';

export function useProcessMountedFile() {
    
    const processFile = async (file) => {
        if (!file) {
            return;
        }
        
        if (file.init_state === 'uninitialized') {
            try {
                await axios.post('/codemate/process-file/init-file', {
                    file_id: file.id
                });
                console.log('File initialization started for:', file.name);
            } catch (error) {
                console.error('Failed to initialize file:', error);
            }
        }
    };
    
    return {
        processFile
    };
}