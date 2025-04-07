import Alert from '@mui/material/Alert';

type SeverityTypes = "success" | "error" | "info" | "warning"

interface AlertProps{
    message: string
    severity: SeverityTypes
}

export default function SimpleAlert({message, severity} : AlertProps) {
  return (
    <Alert severity={severity}>{message}</Alert>
  );
}