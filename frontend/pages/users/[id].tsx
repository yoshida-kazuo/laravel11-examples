import { useRouter } from 'next/router';
import { useEffect, useState } from 'react';
import { User } from '../../types/api';
import apiClient from '../../utils/apiClient';

const UserPage: React.FC = () => {
    const router = useRouter();
    const { id } = router.query;
    const  [user, setUser] = useState<User | null>(null);
    
    useEffect(() => {
        if (id) {
            apiClient.get<User>(`/users/${id}`)
                .then(response => setUser(response.data))
                .catch(error => console.error(error));
        }
    }, [id]);

    if (! user) {
        return <div>Loading...</div>;
    }

    return (
        <div>
            <h1>{user.name}</h1>
            <p>{user.email}</p>
        </div>
    );
};

export default UserPage;
