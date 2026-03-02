FROM python:3.9-slim

WORKDIR /app

RUN pip install --no-cache-dir mysql-connector-python

COPY ./report.py .

CMD ["python", "-u", "report.py"]
