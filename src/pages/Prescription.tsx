import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { useNavigate } from "react-router-dom";
import { Pill } from "lucide-react";

const Prescription = () => {
  const navigate = useNavigate();

  return (
    <div className="container mx-auto px-4 py-8">
      <h1 className="text-3xl font-bold mb-8">Prescription Management</h1>
      
      <Card>
        <CardHeader className="flex flex-row items-center justify-between pb-2">
          <CardTitle className="text-lg font-medium">Prescription Dashboard</CardTitle>
          <Pill className="h-5 w-5 text-muted-foreground" />
        </CardHeader>
        <CardContent>
          <div className="space-y-4">
            <Button className="w-full">Create New Prescription</Button>
            <Button className="w-full">View Patient Prescriptions</Button>
            <Button className="w-full">Prescription History</Button>
            <Button 
              variant="outline" 
              className="w-full"
              onClick={() => navigate(-1)}
            >
              Back to Dashboard
            </Button>
          </div>
        </CardContent>
      </Card>
    </div>
  );
};

export default Prescription;