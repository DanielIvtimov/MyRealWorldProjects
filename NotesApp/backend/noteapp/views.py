from django.shortcuts import render
from noteapp.models import Note
from noteapp.serializers import NoteSerializer
from rest_framework.response import Response 
from rest_framework.decorators import api_view
from rest_framework import status

# Create your views here.
@api_view(["GET, POST,"])
def notes (reqeust):
    if reqeust.method == "GET":
        notes = Note.objects.all()
        serializer = NoteSerializer(notes, many=true)  
        return Response(serializer.data)
    elif reqeust.method == "POST":
        serializer = NoteSerializer(data=reqeust.data)
        if serializer.is_valid():
            serializer.save()
            return Response(serializer.data, status=status.HTTP_201_CREATED)
        return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)